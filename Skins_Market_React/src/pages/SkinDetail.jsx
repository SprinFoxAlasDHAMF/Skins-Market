import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn } from "../utils/auth";
import "../styles/SkinDetail.css";
import Visor3D from "../Modelo3D";
import { useTranslation } from "react-i18next";

function SkinDetail() {
  const navigate = useNavigate();
  const { id } = useParams();
  const { t } = useTranslation();

  const [item, setItem] = useState(null);
  const [exteriores, setExteriores] = useState([]);
  const [armasFiltradas, setArmasFiltradas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [isFavorito, setIsFavorito] = useState(false);
  const [exteriorActivo, setExteriorActivo] = useState(null);
  const [mostrar3D, setMostrar3D] = useState(false);

  useEffect(() => {
    if (!isLoggedIn()) {
      window.location.href = "/login";
      return;
    }

    api.get(`/skins/${id}`)
      .then(res => {
        const dataItem = res.data.item || res.data;
        setItem(dataItem);
        setExteriores(res.data.exteriores || []);
      })
      .catch(err => console.error("Error cargando detalles:", err))
      .finally(() => setLoading(false));

    api.get("/favoritos")
      .then(res => {
        const favIds = res.data.map(f => f.id);
        setIsFavorito(favIds.includes(parseInt(id)));
      })
      .catch(err => console.error("Error verificando favoritos:", err));
  }, [id]);

  const handleAbrir3D = () => {
    if (!item.modelo_3d) {
      alert(t("no_3d"));
      return;
    }
    setMostrar3D(true);
  };

  const filtrarExterior = (exteriorId) => {
    setExteriorActivo(exteriorId);

    api.get(`/skins/${id}/exterior/${exteriorId}`)
      .then(res => setArmasFiltradas(res.data.armas))
      .catch(err => console.error(err));
  };

  const toggleFavorito = () => {
    api.post(`/favoritos/toggle/${id}`)
      .then(res => {
        const favoritos = res.data.favoritos;
        setIsFavorito(favoritos.includes(Number(id)));
      })
      .catch(err => console.error("Error toggling favorito:", err));
  };

  if (loading) return <p className="loading-message">{t("loading_details")}</p>;
  if (!item) return <p className="empty-state">{t("not_found")}</p>;

  const firstArma = item.armas?.[0];

  return (
    <div className="skin-detail-container">

      <div className="skin-detail-header">
        <button className="btn-back" onClick={() => navigate("/skins")}>
          ← {t("back_to_skins")}
        </button>

        <button
          className={`btn-favorito ${isFavorito ? "active" : ""}`}
          onClick={toggleFavorito}
        >
          {isFavorito ? "❤️" : "🤍"}{" "}
          {isFavorito ? t("in_favorites") : t("add_favorite")}
        </button>
      </div>

      <div className="skin-detail-main">
        <div className="skin-detail-image">
          <img
            src={item.foto ? `http://localhost:8000/${item.foto}` : "https://via.placeholder.com/350"}
            alt={item.nombre}
          />

          <button
            className={`btn-3d ${!item.modelo_3d ? "disabled" : ""}`}
            onClick={handleAbrir3D}
          >
            {item.modelo_3d ? t("view_3d") : t("no_3d_btn")}
          </button>
        </div>

        <div className="skin-detail-info">
          <h2>🎮 {item.nombre}</h2>

          <div className="skin-price">${item.precio}</div>

          <div className="skin-attributes">
            <div>
              <span>{t("quality")}:</span>
              <span>{item.calidad?.nombre || "N/A"}</span>
            </div>

            <div>
              <span>{t("exterior")}:</span>
              <span>{firstArma?.exterior?.nombre || "N/A"}</span>
            </div>
          </div>

          {firstArma?.pegatinas?.length > 0 && (
            <div className="skin-pegatinas">
              <h5>{t("stickers")}:</h5>

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
                    />
                    <small>{p.modoPegatina?.nombre}</small>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>

      {/* filtros exterior */}
      {exteriores.length > 0 && (
        <div className="exterior-filters">
          <h5>{t("filter_exterior")}</h5>

          <div className="exterior-buttons">
            {exteriores.map(ext => (
              <button
                key={ext.id}
                className={`btn-exterior ${exteriorActivo === ext.id ? "active" : ""}`}
                onClick={() => filtrarExterior(ext.id)}
              >
                {ext.nombre}
              </button>
            ))}
          </div>
        </div>
      )}

      {exteriorActivo && armasFiltradas.length === 0 && (
        <p className="empty-state">{t("no_variants")}</p>
      )}

      {armasFiltradas.length > 0 && (
        <div className="armas-section">
          <h5>{t("variants")}</h5>

          <div className="armas-grid">
            {armasFiltradas.map((arma, idx) => (
              <div key={idx} className="arma-card">
                <img src={arma.foto ? `http://localhost:8000/${arma.foto}` : "https://via.placeholder.com/280"} />

                <div className="arma-card-body">
                  <h6>{arma.nombre}</h6>
                  <p><strong>${arma.precio}</strong></p>

                  <p>{t("category")}: {arma.categoria}</p>
                  <p>{t("exterior")}: {arma.exterior}</p>

                  <button onClick={() => navigate(`/skins/${arma.id}`)}>
                    {t("view_details")}
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {mostrar3D && item.modelo_3d && (
        <div className="modal-3d-overlay">
          <div className="modal-3d-content">
            <button onClick={() => setMostrar3D(false)}>
              ✖ {t("close")}
            </button>

            <Visor3D
              modeloUrl={`http://localhost:8000/api/modelos/${item.modelo_3d.split("/").pop()}`}
              onClose={() => setMostrar3D(false)}
            />
          </div>
        </div>
      )}
    </div>
  );
}

export default SkinDetail;