import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout, isAdmin } from "../utils/auth";
import "../styles/Skins.css";
import { useTranslation } from "react-i18next";

function Skins() {
  const navigate = useNavigate();
  const [skins, setSkins] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [isDarkMode, setIsDarkMode] = useState(true);
  const { t, i18n } = useTranslation();
  const [modosPegatinas, setModosPegatinas] = useState([]);
  const [modoPegatina, setModoPegatina] = useState("");
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  // Filtros
  const [calidades, setCalidades] = useState([]);
  const [categorias, setCategorias] = useState([]);
  const [exteriores, setExteriores] = useState([]);
  const [colores, setColores] = useState([]);
  const [filters, setFilters] = useState({
    calidad_id: "",
    tipo: "",
    categoria_id: "",
    exterior_id: "",
    color: "",
    precio_min: "",
    precio_max: "",
    tiene_pegatinas: "", // 👈 NUEVO
    order_by: "nombre",
    order_dir: "asc",
  });
  const mostrarSoloPegatinas = modoPegatina !== "";
  // Verifica login y trae filtros
  useEffect(() => {
    if (!isLoggedIn()) {
      navigate("/login");
      return;
    }
    api.get("/modos-pegatinas")
    .then(res => setModosPegatinas(res.data))
    .catch(err => console.error(err));
    api.get("/filters")
      .then(res => {
        setCalidades(res.data.calidades);
        setCategorias(res.data.categorias);
        setExteriores(res.data.exteriores);
        setColores(res.data.colores || []);
      })
      .catch(err => console.error(err));
  }, [navigate]);



  // Traer skins con filtros
  const fetchSkins = (isLoadMore = false, appliedFilters = filters) => {
    setLoading(true);
    const currentPage = isLoadMore ? page + 1 : 1;
    
    api.get("/skins", { 
      params: { 
        ...appliedFilters, // Usamos los filtros procesados
        modo_pegatina_id: modoPegatina,
        page: currentPage,
        per_page: 20 
      } 
    })

      .then(res => {
        // Importante: Laravel devuelve la data en res.data.data si usas ->paginate()
        const newSkins = res.data.data || res.data;
        
        setSkins(prev => isLoadMore ? [...prev, ...newSkins] : newSkins);
        setPage(currentPage);
        
        // Controlar si ocultamos el botón (basado en la respuesta de Laravel)
        // Si res.data.next_page_url es null, ya no hay más skins.
        if (res.data.next_page_url === null || newSkins.length < 20) {
          setHasMore(false);
        } else {
          setHasMore(true);
        }
      })
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  };


  useEffect(() => {
    setPage(1); 
    // Añadimos modoPegatina a la dependencia y al objeto de filtros
    setFilters(prev => ({ ...prev, modo_pegatina_id: modoPegatina }));
  }, [modoPegatina]);

  

  // 1. Debounce: Cuando el usuario escribe, esperamos 300ms y actualizamos el objeto filters
  useEffect(() => {
    const delay = setTimeout(() => {
      setFilters(prev => ({ ...prev, nombre: search }));
    }, 300);
    return () => clearTimeout(delay);
  }, [search]);

  // 2. ÚNICA FUENTE DE VERDAD: Cuando CUALQUIER filtro cambie, pedimos los datos
  useEffect(() => {
    setPage(1);
    
    // Procesamos los valores para el backend
    const filtersToSend = { ...filters };
    if (filters.tiene_pegatinas === "1") filtersToSend.tiene_pegatinas = "si";
    if (filters.tiene_pegatinas === "0") filtersToSend.tiene_pegatinas = "no";
    
    // Añadimos el modo_pegatina_id que está fuera del objeto filters
    filtersToSend.modo_pegatina_id = modoPegatina;

    fetchSkins(false, filtersToSend); 
  }, [filters, modoPegatina]); 
  // Nota: quitamos 'search' de aquí porque ahora dependemos de 'filters.nombre'
  
  // Manejar cambio en filtros
  const handleChange = (e) => setFilters({ ...filters, [e.target.name]: e.target.value });
  useEffect(() => {
    // Esto asegura que el fondo de la página completa cambie, no solo el div
    if (!isDarkMode) {
      document.body.style.backgroundColor = "#ffffff";
    } else {
      document.body.style.backgroundColor = "#040405"; // Tu color oscuro original
    }
  }, [isDarkMode]);
  const handleSubmit = (e) => {
    e.preventDefault();
    fetchSkins();
  };
  const handleReset = () => {
    setSearch("");
    setModoPegatina(""); 

    setFilters({
      calidad_id: "",
      tipo: "",
      categoria_id: "",
      exterior_id: "",
      color_id: "",
      precio_min: "",
      precio_max: "",
      tiene_pegatinas: "",
      nombre: ""
    });
  };
    // Borrar skin (solo admin)
  const handleDelete = (id) => {
    if (!window.confirm("¿Estás seguro de que quieres borrar esta skin?")) return;

    api.delete(`/admin/skins/${id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
    })
      .then(() => setSkins(skins.filter(s => s.id !== id)))
      .catch(err => console.error(err));
  };
  const handleLoadMore = () => {
    if (!loading && hasMore) {
      fetchSkins(true);
    }
  };
  return (
    <div className={`skins-container ${!isDarkMode ? "light-theme" : ""}`}>
      <div className="skins-header">
      <h1>{t("title")}</h1>        
      <div className="lang-switcher">
        <button className="btn-custom btn-secondary-custom" onClick={() => i18n.changeLanguage("es")}>ES</button>
        <button className="btn-custom btn-secondary-custom" onClick={() => i18n.changeLanguage("en")}>EN</button>
      </div>
        <div className="skins-header-actions">
          <button 
            className="btn-custom btn-theme-toggle" 
            onClick={() => setIsDarkMode(!isDarkMode)}
          >
            {isDarkMode ? "☀️ Modo Claro" : "🌙 Modo Oscuro"}
          </button>
          
          <button onClick={logout}>{t("logout")}</button>
          <button onClick={() => navigate("/perfil")}>{t("perfil")}</button>
          <button onClick={() => navigate("/qui-som")}>{t("quien_somos")}</button>
          <button onClick={() => navigate("/favoritos")}>{t("favoritos")}</button>
          {isAdmin() && (
            <button className="btn-custom btn-success-custom" onClick={() => navigate("/admin/skins/new")}>
              Crear Skin
            </button>
          )}
        </div>
        <div className="filter-group">
          <label>{t("ordenar")}</label>

          <select
            name="order_combined"
            value={`${filters.order_by}-${filters.order_dir}`}
            onChange={(e) => {
              const [by, dir] = e.target.value.split("-");
              setFilters(prev => ({ ...prev, order_by: by, order_dir: dir }));
            }}
          >
            <option value="id-asc">{t("sort_none")}</option>

            <option value="nombre-asc">{t("sort_name_asc")}</option>
            <option value="nombre-desc">{t("sort_name_desc")}</option>

            <option value="precio-asc">{t("sort_price_asc")}</option>
            <option value="precio-desc">{t("sort_price_desc")}</option>
          </select>
        </div>
      </div>

      <div className="skins-content">
        {/* FILTROS */}
        
        <div className="skins-filters">
        <h4>{t("filters")}</h4>
         <div className="filter-group">
          <label>{t("search_name")}</label>
            <input
              type="text"
              placeholder={t("search_placeholder")}
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
        </div>
        <div className="filter-group">
        <label>{t("stickers")}</label>
          <select
            name="tiene_pegatinas"
            value={filters.tiene_pegatinas}
            onChange={handleChange}
          >
            <option value="">{t("all")}</option>
            <option value="1">{t("with_stickers")}</option>
            <option value="0">{t("without_stickers")}</option>
          </select>
        </div>
        <div className="filter-group">
        <label>{t("sticker_mode")}</label>

          <select
            value={modoPegatina}
            onChange={(e) => setModoPegatina(e.target.value)}
          >
            <option value="">{t("all")}</option>
            {modosPegatinas.map((m) => (
              <option key={m.id} value={m.id}>
                {m.nombre}
              </option>
            ))}
          </select>
        </div>
          <form onSubmit={handleSubmit}>
            <div className="filter-group">
              <label>{t("calidad")}</label>
              <select name="calidad_id" value={filters.calidad_id} onChange={handleChange}>
              <option value="">{t("select_quality")}</option>
                {calidades.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select> 
            </div>

            <div className="filter-group">
              <label>{t("tipo")}</label>
              <select name="tipo" value={filters.tipo} onChange={handleChange}>
              <option value="">{t("select_type")}</option>
              <option value="arma">{t("weapon")}</option>
              <option value="guantes">{t("gloves")}</option>
              <option value="agente">{t("agent")}</option>
              </select>
            </div>

            <div className="filter-group">
              <label>{t("categoria")}</label>
              <select name="categoria_id" value={filters.categoria_id} onChange={handleChange}>
              <option value="">{t("select_category")}</option>
                {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select>
            </div>

            <div className="filter-group">
              <label>{t("exterior")}</label>
              <select name="exterior_id" value={filters.exterior_id} onChange={handleChange}>
              <option value="">{t("select_exterior")}</option>
                {exteriores.map(e => <option key={e.id} value={e.id}>{e.nombre}</option>)}
              </select>
            </div>

            <div className="filter-group">
              <label>{t("color")}</label>
              <select 
                name="color_id" 
                value={filters.color_id || ""} 
                onChange={handleChange}
              >
                <option value="">{t("select_color")}</option>
                {colores.map(c => (
                  <option key={c.id} value={c.id}>
                    {c.nombre}
                  </option>
                ))}
              </select>
            </div>

            <div className="filter-group">
            <label>{t("min_price")}</label>
              <input type="number" name="precio_min" value={filters.precio_min} onChange={handleChange} placeholder="$0"/>
            </div>

            <div className="filter-group">
            <label>{t("max_price")}</label>
              <input type="number" name="precio_max" value={filters.precio_max} onChange={handleChange} placeholder="$9999"/>
            </div>


            <button type="button" className="btn-apply-filters" onClick={handleReset}>  {t("reset_filters")}</button>
          </form>
        </div>

        {/* RESULTADOS */}
        <div className="skins-results">
        <h4>{t("available_skins")}</h4>
          {loading && <p className="loading-text">{t("loading")}</p>}
          {!loading && skins.length === 0 && <p className="no-results-text">{t("no_results")}</p>}

        <div className="skins-grid">
          {skins.map((item) => (
            <div key={`${item.tipo_item}-${item.id}`} className="skin-card">
            <img
                  src={`http://localhost:8000/${item.imagen || item.foto}`}
                  alt={item.nombre}
                />
              {/* Dentro del map de skins */}
              <div className="skin-card-body">
                <h5>{item.nombre}</h5>
                <p><strong>${item.precio}</strong></p>
                
                <p>
                  <span>{item.tipo_item === 'skin' ? 'Calidad:' : 'Modo:'}</span> 
                  {item.info_extra} 
                </p>

                {item.tipo_item === 'skin' ? (
                  <button onClick={() => navigate(`/skins/${item.id}`)}>  {t("view_skin")}</button>
                ) : (
                  <button onClick={() => navigate(`/pegatinas/${item.id}`)}>  {t("view_sticker")}</button>
                )}
              </div>
            </div>
          ))}
        </div>
            </div>
          </div>
            {!mostrarSoloPegatinas && skins.length > 0 && hasMore && (
                <div className="load-more-container">
                    <button 
                        className="btn-load-more" 
                        onClick={handleLoadMore} 
                        disabled={loading}
                    >
                    {loading ? t("loading") : t("load_more")}
                    </button>
                </div>
            )}
        </div>

  );
}

export default Skins;
