import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout } from "../utils/auth";
import "../styles/Favoritos.css";

function Favoritos() {
  const navigate = useNavigate();
  const [favoritos, setFavoritos] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!isLoggedIn()) {
      navigate("/login");
      return;
    }

    fetchFavoritos();
  }, [navigate]);

  const fetchFavoritos = () => {
    setLoading(true);
    api.get("/favoritos")
      .then(res => {
        console.log("Favoritos obtenidos:", res.data);
        setFavoritos(res.data);
      })
      .catch(err => {
        console.error("Error cargando favoritos:", err);
        setFavoritos([]);
      })
      .finally(() => setLoading(false));
  };

  const handleRemoveFavorito = (itemId) => {
    api.post(`/favoritos/toggle/${itemId}`)
      .then(() => {
        setFavoritos(favoritos.filter(f => f.id !== itemId));
      })
      .catch(err => console.error("Error removiendo favorito:", err));
  };

  return (
    <div className="favoritos-container">
      <div className="favoritos-header">
        <h1>❤️ Mis Favoritos</h1>
        <div className="favoritos-header-actions">
          <button className="btn-custom btn-secondary-custom" onClick={() => navigate("/skins")}>← Volver a Skins</button>
          <button className="btn-custom btn-secondary-custom" onClick={() => navigate("/perfil")}>Mi Perfil</button>
          <button className="btn-custom btn-secondary-custom" onClick={logout}>Logout</button>
        </div>
      </div>

      {loading && <p className="loading-text">Cargando favoritos...</p>}
      
      {!loading && favoritos.length === 0 && (
        <div className="empty-state">
          <p>No tienes favoritos aún</p>
          <button className="btn-custom btn-primary-custom" onClick={() => navigate("/skins")}>
            Explorar Skins
          </button>
        </div>
      )}

      {!loading && favoritos.length > 0 && (
        <div className="favoritos-section">
          <p className="favoritos-count">Total: <strong>{favoritos.length}</strong> favorito{favoritos.length !== 1 ? 's' : ''}</p>
          
          <div className="favoritos-grid">
            {favoritos.map(skin => (
              <div key={skin.id} className="favorito-card">
                <div className="favorito-image-wrapper">
                  <img
                    src={`http://localhost:8000/${skin.foto}`}
                    alt={skin.nombre}
                    onError={(e) => e.target.src = 'https://via.placeholder.com/280x280?text=Sin+imagen'}
                  />
                  <button 
                    className="btn-remove-favorito"
                    onClick={() => handleRemoveFavorito(skin.id)}
                    title="Remover de favoritos"
                  >
                    x
                  </button>
                </div>
                
                <div className="favorito-card-body">
                  <h5>{skin.nombre}</h5>
                  <p className="precio"><strong>${skin.precio}</strong></p>
                  {skin.calidad && <p><span>Calidad:</span> <strong>{skin.calidad}</strong></p>}
                  {skin.categoria && <p><span>Categoría:</span> <strong>{skin.categoria}</strong></p>}
                  {skin.exterior && <p><span>Exterior:</span> <strong>{skin.exterior}</strong></p>}
                  {skin.color && <p><span>Color:</span> <strong>{skin.color}</strong></p>}

                  <div className="favorito-card-actions">
                    <button 
                      className="btn-view-details" 
                      onClick={() => navigate(`/skins/${skin.id}`)}
                    >
                      Ver Detalles
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

export default Favoritos;
