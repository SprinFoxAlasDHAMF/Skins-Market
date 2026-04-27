import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout, isAdmin } from "../utils/auth";
import "../styles/Skins.css";


function Skins() {
  const navigate = useNavigate();
  const [skins, setSkins] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [pegatinas, setPegatinas] = useState([]);
  const [modosPegatinas, setModosPegatinas] = useState([]);
  const [modoPegatina, setModoPegatina] = useState("");
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
  });

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
  const fetchSkins = () => {
    setLoading(true);
    api.get("/skins", { params: filters })
      .then(res => setSkins(res.data))
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    api.get("/pegatinas", {
      params: {
        modo_pegatina_id: modoPegatina
      }
    })
      .then(res => setPegatinas(res.data))
      .catch(err => console.error(err));
  }, [modoPegatina]);

  useEffect(() => {
    fetchSkins();
  }, [filters]);
  useEffect(() => {
    const delay = setTimeout(() => {
      setFilters(prev => ({ ...prev, nombre: search }));
    }, 300);

    return () => clearTimeout(delay);
  }, [search]);
  
  // Manejar cambio en filtros
  const handleChange = (e) => setFilters({ ...filters, [e.target.name]: e.target.value });

  const handleSubmit = (e) => {
    e.preventDefault();
    fetchSkins();
  };
  const handleReset = () => {
    setSearch("");
    setModoPegatina("");   // 🔥 IMPORTANTE
    setPegatinas([]);      // 🔥 limpia cache

    setFilters({
      calidad_id: "",
      tipo: "",
      categoria_id: "",
      exterior_id: "",
      color: "",
      precio_min: "",
      precio_max: "",
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

  return (
    <div className="skins-container">
      <div className="skins-header">
        <h1>Skins CS:GO</h1>
        <div className="skins-header-actions">
          <button className="btn-custom btn-secondary-custom" onClick={logout}>Logout</button>
          <button className="btn-custom btn-secondary-custom" onClick={() => navigate("/perfil")}>Mi Perfil</button>
          {!isAdmin() && (
            <button className="btn-custom btn-secondary-custom" onClick={() => navigate("/favoritos")}>Favoritos</button>
          )}
          {isAdmin() && (
            <button className="btn-custom btn-success-custom" onClick={() => navigate("/admin/skins/new")}>
              Crear Skin
            </button>
          )}
        </div>
      </div>

      <div className="skins-content">
        {/* FILTROS */}
        
        <div className="skins-filters">
          <h4>Filtros</h4>
          <div className="filter-group">
            <label>Buscar por nombre</label>
            <input
              type="text"
              placeholder="Escribe el nombre de la skin"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
        </div>
        <div className="filter-group">
          <label>Modo de Pegatina</label>

          <select
            value={modoPegatina}
            onChange={(e) => setModoPegatina(e.target.value)}
          >
            <option value="">Todos</option>

            {modosPegatinas.map((m) => (
              <option key={m.id} value={m.id}>
                {m.nombre}
              </option>
            ))}
          </select>
        </div>
          <form onSubmit={handleSubmit}>
            <div className="filter-group">
              <label>Calidad</label>
              <select name="calidad_id" value={filters.calidad_id} onChange={handleChange}>
                <option value="">Seleccionar calidad</option>
                {calidades.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select> 
            </div>

            <div className="filter-group">
              <label>Tipo</label>
              <select name="tipo" value={filters.tipo} onChange={handleChange}>
                <option value="">Seleccionar tipo</option>
                <option value="arma">Arma</option>
                <option value="guantes">Guantes</option>
                <option value="agente">Agente</option>
              </select>
            </div>

            <div className="filter-group">
              <label>Categoría</label>
              <select name="categoria_id" value={filters.categoria_id} onChange={handleChange}>
                <option value="">Seleccionar categoría</option>
                {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select>
            </div>

            <div className="filter-group">
              <label>Exterior</label>
              <select name="exterior_id" value={filters.exterior_id} onChange={handleChange}>
                <option value="">Seleccionar exterior</option>
                {exteriores.map(e => <option key={e.id} value={e.id}>{e.nombre}</option>)}
              </select>
            </div>

            <div className="filter-group">
              <label>Color</label>
              <select name="color" value={filters.color} onChange={handleChange}>
                <option value="">Seleccionar color</option>
                {colores.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select>
            </div>

            <div className="filter-group">
              <label>Precio mínimo</label>
              <input type="number" name="precio_min" value={filters.precio_min} onChange={handleChange} placeholder="$0"/>
            </div>

            <div className="filter-group">
              <label>Precio máximo</label>
              <input type="number" name="precio_max" value={filters.precio_max} onChange={handleChange} placeholder="$9999"/>
            </div>
            <button type="button" className="btn-apply-filters" onClick={handleReset}>Resetear Filtros</button>
          </form>
        </div>

        {/* RESULTADOS */}
        <div className="skins-results">
          <h4>Skins Disponibles</h4>
          {loading && <p className="loading-text">Cargando skins...</p>}
          {!loading && skins.length === 0 && <p className="no-results-text">No se encontraron skins</p>}

          <div className="skins-grid">
            {!loading && skins.map(skin => (
              <div key={skin.id} className="skin-card">
                <img
                  src={`http://localhost:8000/${skin.foto}`}
                  alt={skin.nombre}
                  onError={(e) => e.target.src = 'https://via.placeholder.com/280x280?text=Sin+imagen'}
                />
                <div className="skin-card-body">
                  <h5>{skin.nombre}</h5>
                  <p><strong>${skin.precio}</strong></p>
                  {skin.calidad && <p><span>Calidad:</span> <strong>{skin.calidad}</strong></p>}
                  {skin.categoria && <p><span>Categoría:</span> <strong>{skin.categoria}</strong></p>}
                  {skin.exterior && <p><span>Exterior:</span> <strong>{skin.exterior}</strong></p>}
                  {skin.color && <p><span>Color:</span> <strong>{skin.color}</strong></p>}

                  <div className="skin-card-actions">
                    {isAdmin() && (
                      <>
                        <button className="btn-edit" onClick={() => navigate(`/admin/skins/${skin.id}/edit`)}>
                          Editar
                        </button>
                        <button className="btn-delete" onClick={() => handleDelete(skin.id)}>
                          Borrar
                        </button>
                      </>
                    )}
                    <button className="btn-view-details" onClick={() => navigate(`/skins/${skin.id}`)}>
                      Ver Detalles
                    </button>
                  </div>
                </div>
              </div>
            ))}
            {/* PEGATINAS */}
<div className="skins-results">
  <h4>Pegatinas Disponibles</h4>

  {pegatinas.length === 0 && <p className="no-results-text">No hay pegatinas</p>}

  <div className="skins-grid">
    {pegatinas.map(p => (
      <div key={p.id} className="skin-card">
        <img
          src={`http://localhost:8000/${p.imagen}`}
          alt={p.nombre}
          onError={(e) =>
            (e.target.src = "https://via.placeholder.com/280x280?text=Sin+imagen")
          }
        />

        <div className="skin-card-body">
          <h5>{p.nombre}</h5>
          <p><strong>${p.precio}</strong></p>
          <p>
            <span>Modo:</span>{" "}
            <strong>{p.modo?.nombre || "Normal"}</strong>
          </p>
          <button
            className="btn-view-details"
            onClick={() => navigate(`/pegatinas/${p.id}`)}
          >
            Ver Detalles
          </button>
        </div>
      </div>
    ))}
  </div>
</div>
          </div>
        </div>
        
      </div>
    </div>
  );
}

export default Skins;
