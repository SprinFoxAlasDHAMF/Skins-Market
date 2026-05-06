import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout } from "../utils/auth";
import "../styles/SkinDetail.css";
import Visor3D from "../Modelo3D";
function SkinDetail() {
  const navigate = useNavigate();
  const { id } = useParams(); // id de la skin
  const [item, setItem] = useState(null);
  const [exteriores, setExteriores] = useState([]);
  const [armasFiltradas, setArmasFiltradas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [isFavorito, setIsFavorito] = useState(false);
  const [exteriorActivo, setExteriorActivo] = useState(null); // ✅ NUEVO
  const [mostrar3D, setMostrar3D] = useState(false);

  useEffect(() => {
    if (!isLoggedIn()) {
      window.location.href = "/login";
      return;
    }

    // Traer detalle de la skin
    api.get(`/skins/${id}`)
        .then(res => {
            const dataItem = res.data.item || res.data;
            setItem(dataItem);
            setExteriores(res.data.exteriores || []);
          })
          .catch(err => console.error("Error cargando detalles:", err))
          .finally(() => setLoading(false));

    // Verificar si es favorito
    api.get("/favoritos")
      .then(res => {
        const favIds = res.data.map(f => f.id);
        setIsFavorito(favIds.includes(parseInt(id)));
      })
      .catch(err => console.error("Error verificando favoritos:", err));
  }, [id]);
const handleAbrir3D = () => {
    if (!item.modelo_3d) {
      alert("⚠️ Esta skin no tiene un modelo 3D disponible actualmente.");
      return;
    }
    setMostrar3D(true);
  };
  // Filtrar por exterior
  const filtrarExterior = (exteriorId) => {
    setExteriorActivo(exteriorId); // ✅ NUEVO

    api.get(`/skins/${id}/exterior/${exteriorId}`)
      .then(res => setArmasFiltradas(res.data.armas))
      .catch(err => console.error(err));
  };

  // Toggle favorito
  const toggleFavorito = () => {
    api.post(`/favoritos/toggle/${id}`)
      .then(res => {
        const favoritos = res.data.favoritos;

        setIsFavorito(favoritos.includes(Number(id)));
      })
      .catch(err => console.error("Error toggling favorito:", err));
  };

  if (loading) return <p className="loading-message">⏳ Cargando detalles...</p>;
  if (!item) return <p className="empty-state">❌ Skin no encontrada</p>;

  const firstArma = item.armas?.[0];

  return (
    <div className="skin-detail-container">
      <div className="skin-detail-header">
        <button className="btn-back" onClick={() => navigate("/skins")}>← Volver a Skins</button>
        <button 
          className={`btn-favorito ${isFavorito ? 'active' : ''}`}
          onClick={toggleFavorito}
          title={isFavorito ? "Remover de favoritos" : "Agregar a favoritos"}
        >
          {isFavorito ? '❤️' : '🤍'} {isFavorito ? 'En Favoritos' : 'Agregar a Favoritos'}
        </button>
      </div>

      <div className="skin-detail-main">
        <div className="skin-detail-image">
          <img
            src={item.foto ? `http://localhost:8000/${item.foto}` : 'https://via.placeholder.com/350'}
            alt={item.nombre}
            onError={(e) => e.target.src = 'https://via.placeholder.com/350?text=Sin+imagen'}
          />
          <button 
            className={`btn-3d ${!item.modelo_3d ? 'disabled' : ''}`} 
            onClick={handleAbrir3D}
          >
            {item.modelo_3d ? 'Ver en 3D' : '🚫 Sin 3D'}
          </button>
        </div>

        <div className="skin-detail-info">
          <div>
            <h2>🎮 {item.nombre}</h2>
            <div className="skin-price">${item.precio}</div>

            <div className="skin-attributes">
              <div className="skin-attribute">
                <span className="skin-attribute-label">Calidad:</span>
                <span className="skin-attribute-value">{item.calidad?.nombre || 'N/A'}</span>
              </div>
              <div className="skin-attribute">
                <span className="skin-attribute-label">Exterior:</span>
                <span className="skin-attribute-value">{firstArma?.exterior?.nombre || 'N/A'}</span>
              </div>
            </div>
            {firstArma?.pegatinas?.length > 0 && (
  <div className="skin-pegatinas">
    <h5>Pegatinas:</h5>

    <div className="pegatinas-grid">
      {firstArma.pegatinas.map((p) => (
        <div
          key={p.id}
          className="pegatina-item clickable"
          onClick={() => navigate(`/pegatinas/${p.id}`)}
        >
          <img
            src={
              p.imagen
                ? `http://localhost:8000/${p.imagen}`
                : "https://via.placeholder.com/60"
            }
            alt={p.modoPegatina?.nombre || "pegatina"}
            onError={(e) => {
              e.target.src = "https://via.placeholder.com/60";
            }}
          />

          <small>{p.modoPegatina?.nombre}</small>
        </div>
      ))}
    </div>
  </div>
)}
          </div>
        </div>
      </div>

      {/* Filtro por Exterior */}
      {exteriores.length > 0 && (
        <div className="exterior-filters">
          <h5>Filtrar por Exterior:</h5>
          <div className="exterior-buttons">
            {exteriores.map(ext => (
              <button
                key={ext.id}
                className={`btn-exterior ${exteriorActivo === ext.id ? 'active' : ''}`} // ✅ FIX
                onClick={() => filtrarExterior(ext.id)}
              >
                {ext.nombre}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* 🔴 MENSAJE SI NO HAY RESULTADOS */}
      {exteriorActivo && armasFiltradas.length === 0 && (
        <p className="empty-state">❌ No hay armas con este exterior</p>
      )}

      {/* Armas filtradas */}
      {armasFiltradas.length > 0 && (
        <div className="armas-section">
          <h5>✨ Variantes Disponibles</h5>
          <div className="armas-grid">
            {armasFiltradas.map((arma, idx) => (
              <div key={idx} className="arma-card">
                <img
                  src={arma.foto ? `http://localhost:8000/${arma.foto}` : 'https://via.placeholder.com/280'}
                  alt={arma.nombre}
                  onError={(e) => e.target.src = 'https://via.placeholder.com/280?text=Sin+imagen'}
                />
                <div className="arma-card-body">
                  <h6>{arma.nombre}</h6>
                  <p><strong>${arma.precio}</strong></p>
                  <p><strong>Categoría:</strong> {arma.categoria}</p>
                  <p><strong>Exterior:</strong> {arma.exterior}</p>

                  {arma.pegatinas?.length > 0 && (
                    <div className="arma-card-pegatinas">
                      {arma.pegatinas.map((p, i) => (
                        <span key={i} className="pegatina-badge">{p.nombre}</span>
                      ))}
                    </div>
                  )}

                <button 
                  className="btn-view-details" 
                  onClick={() => navigate(`/skins/${arma.id}`)}
                >
                  Ver Detalles
                </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
{/* Al final de tu componente SkinDetail.jsx */}
{mostrar3D && item.modelo_3d && (
        <div className="modal-3d-overlay"> 
          <div className="modal-3d-content">
            <button className="btn-close-3d" onClick={() => setMostrar3D(false)}>✖ Cerrar</button>
            <Visor3D 
              modeloUrl={`http://localhost:8000/api/modelos/${item.modelo_3d.split('/').pop()}`} 
              onClose={() => setMostrar3D(false)} 
            />
          </div>
        </div>
      )}
    </div>
  );
}

export default SkinDetail;