import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout } from "../utils/auth";
import "../styles/Favoritos.css";
import { useTranslation } from "react-i18next";

function Favoritos() {
  const navigate = useNavigate();
  const { t } = useTranslation();

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
        <h1>{t("favorites_title")}</h1>

        <div className="favoritos-header-actions">
          <button onClick={() => navigate("/skins")}>
            ← {t("back_to_skins")}
          </button>

          <button onClick={() => navigate("/perfil")}>
            {t("profile")}
          </button>

          <button onClick={logout}>
            {t("logout")}
          </button>
        </div>
      </div>

      {loading && (
        <p className="loading-text">{t("loading_favorites")}</p>
      )}

      {!loading && favoritos.length === 0 && (
        <div className="empty-state">
          <p>{t("no_favorites")}</p>

          <button onClick={() => navigate("/skins")}>
            {t("explore_skins")}
          </button>
        </div>
      )}

      {!loading && favoritos.length > 0 && (
        <div className="favoritos-section">

          <p className="favoritos-count">
            {t("total")}: <strong>{favoritos.length}</strong> {t("favorites")}
          </p>

          <div className="favoritos-grid">
            {favoritos.map(skin => (
              <div key={skin.id} className="favorito-card">

                <div className="favorito-image-wrapper">
                  <img
                    src={`http://localhost:8000/${skin.foto}`}
                    alt={skin.nombre}
                  />

                  <button
                    className="btn-remove-favorito"
                    onClick={() => handleRemoveFavorito(skin.id)}
                  >
                    x
                  </button>
                </div>

                <div className="favorito-card-body">
                  <h5>{skin.nombre}</h5>

                  <p className="precio">
                    <strong>${skin.precio}</strong>
                  </p>

                  {skin.calidad && (
                    <p>{t("quality")}: <strong>{skin.calidad}</strong></p>
                  )}

                  {skin.categoria && (
                    <p>{t("category")}: <strong>{skin.categoria}</strong></p>
                  )}

                  {skin.exterior && (
                    <p>{t("exterior")}: <strong>{skin.exterior}</strong></p>
                  )}

                  {skin.color && (
                    <p>{t("color")}: <strong>{skin.color}</strong></p>
                  )}

                  <div className="favorito-card-actions">
                    <button onClick={() => navigate(`/skins/${skin.id}`)}>
                      {t("view_details")}
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