import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout, isAdmin } from "../utils/auth";

function Skins() {
  const navigate = useNavigate();
  const [skins, setSkins] = useState([]);
  const [loading, setLoading] = useState(true);

  // Filtros
  const [calidades, setCalidades] = useState([]);
  const [categorias, setCategorias] = useState([]);
  const [exteriores, setExteriores] = useState([]);
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

    api.get("/filters")
      .then(res => {
        setCalidades(res.data.calidades);
        setCategorias(res.data.categorias);
        setExteriores(res.data.exteriores);
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
    fetchSkins();
  }, []); // al cargar la página

  // Manejar cambio en filtros
  const handleChange = (e) => setFilters({ ...filters, [e.target.name]: e.target.value });

  const handleSubmit = (e) => {
    e.preventDefault();
    fetchSkins();
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
    <div className="container mt-5">
      <h1>Skins de CS:GO</h1>
        
      <div style={{ marginBottom: "1rem" }}>
        <button className="btn btn-secondary me-2" onClick={logout}>Logout</button>
        {isAdmin() && (
          <button className="btn btn-success" onClick={() => navigate("/admin/skins/new")}>
            Crear Skin / Arma
          </button>
        )}
      </div>

      <div className="row mb-4">
        {/* FILTROS */}
        <div className="col-md-4">
          <h4>Filtros</h4>
          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label>Calidad</label>
              <select name="calidad_id" value={filters.calidad_id} onChange={handleChange} className="form-control">
                <option value="">Seleccionar calidad</option>
                {calidades.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select>
            </div>

            <div className="mb-3">
              <label>Tipo</label>
              <select name="tipo" value={filters.tipo} onChange={handleChange} className="form-control">
                <option value="">Seleccionar tipo</option>
                <option value="arma">Arma</option>
                <option value="guantes">Guantes</option>
                <option value="agente">Agente</option>
              </select>
            </div>

            <div className="mb-3">
              <label>Categoría</label>
              <select name="categoria_id" value={filters.categoria_id} onChange={handleChange} className="form-control">
                <option value="">Seleccionar categoría</option>
                {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
              </select>
            </div>

            <div className="mb-3">
              <label>Exterior</label>
              <select name="exterior_id" value={filters.exterior_id} onChange={handleChange} className="form-control">
                <option value="">Seleccionar exterior</option>
                {exteriores.map(e => <option key={e.id} value={e.id}>{e.nombre}</option>)}
              </select>
            </div>

            <div className="mb-3">
              <label>Color</label>
              <input type="text" name="color" value={filters.color} onChange={handleChange} placeholder="Ej: Rojo, Azul" className="form-control"/>
            </div>

            <div className="mb-3">
              <label>Precio mínimo</label>
              <input type="number" name="precio_min" value={filters.precio_min} onChange={handleChange} className="form-control"/>
            </div>

            <div className="mb-3">
              <label>Precio máximo</label>
              <input type="number" name="precio_max" value={filters.precio_max} onChange={handleChange} className="form-control"/>
            </div>

            <button type="submit" className="btn btn-primary">Aplicar filtros</button>
          </form>
        </div>

        {/* RESULTADOS */}
        <div className="col-md-8">
          <h4>Skins Disponibles</h4>
          {loading && <p>Cargando skins...</p>}
          {!loading && skins.length === 0 && <p>No se encontraron skins con los filtros aplicados.</p>}

          <div className="row">
            {!loading && skins.map(skin => (
              <div key={skin.id} className="col-md-4 mb-4">
                <div className="card">
                <img
                src={skin.foto ? `http://localhost:8000/storage/${skin.foto}` : '/placeholder.png'}
                className="card-img-top"
                alt={skin.nombre}
                />
                  <div className="card-body">
                    <h5 className="card-title">{skin.nombre}</h5>
                    <p>Precio: ${skin.precio}</p>
                    <p>Calidad: {skin.calidad}</p>
                    <p>Categoría: {skin.categoria}</p>
                    <p>Exterior: {skin.exterior}</p>
                    <p>Color: {skin.color}</p>

                    {isAdmin() && (
                      <div className="mt-2">
                        <button className="btn btn-warning btn-sm me-2" onClick={() => navigate(`/admin/skins/${skin.id}/edit`)}>
                          Editar
                        </button>
                        <button className="btn btn-danger btn-sm" onClick={() => handleDelete(skin.id)}>
                          Borrar
                        </button>
                      </div>
                    )}
                  </div>
                  <button className="btn btn-primary mt-2 w-100" onClick={() => navigate(`/skins/${skin.id}`)}>
                    Ver detalles
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

export default Skins;
