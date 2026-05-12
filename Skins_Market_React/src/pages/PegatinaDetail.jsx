import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../api/api";
import { useTranslation } from "react-i18next";

function PegatinaDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const [pegatina, setPegatina] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get(`/pegatinas/${id}`)
      .then(res => {
        setPegatina(res.data);
      })
      .catch(err => {
        console.error("Error cargando pegatina:", err);
        setPegatina(null);
      })
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) return <p>{t("loading_sticker")}</p>;
  if (!pegatina) return <p>{t("sticker_not_found")}</p>;

  return (
    <div className="skin-detail-container">

      <div className="skin-detail-header">
        <button onClick={() => navigate(-1)}>
          ← {t("back")}
        </button>
      </div>

      <div className="skin-detail-main">

        <div className="skin-detail-image">
          <img
            src={
              pegatina.imagen
                ? `http://localhost:8000/${pegatina.imagen}`
                : "https://via.placeholder.com/300"
            }
            alt={pegatina.nombre}
          />
        </div>

        <div className="skin-detail-info">
          <h2>🟣 {pegatina.nombre}</h2>

          <div className="skin-price">
            ${pegatina.precio ?? "0.00"}
          </div>

          <p>
            <strong>{t("mode")}:</strong>{" "}
            {pegatina.modo ?? "N/A"}
          </p>

          <button onClick={() => navigate("/skins")}>
            {t("view_skins")}
          </button>

        </div>

      </div>
    </div>
  );
}

export default PegatinaDetail;