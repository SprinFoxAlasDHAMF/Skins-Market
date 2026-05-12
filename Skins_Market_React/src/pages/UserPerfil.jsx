import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import "../styles/UserPerfil.css";
import { useTranslation } from "react-i18next";

function UserPerfil() {
  const navigate = useNavigate();
  const { t } = useTranslation();

  const [nombre, setNombre] = useState("");
  const [foto, setFoto] = useState(null);
  const [preview, setPreview] = useState(null);
  const [user, setUser] = useState(null);
  const [errores, setErrores] = useState({});

  const [nombreInicial, setNombreInicial] = useState("");
  const [fotoInicial, setFotoInicial] = useState(null);
  const [hasChanges, setHasChanges] = useState(false);

  useEffect(() => {
    api.get("/user")
      .then(res => {
        setUser(res.data);
        setNombre(res.data.nombre);
        setNombreInicial(res.data.nombre);
        setFotoInicial(res.data.foto_perfil);

        if (res.data.foto_perfil) {
          setPreview(`http://localhost:8000/storage/${res.data.foto_perfil}`);
        }
      })
      .catch(() => navigate("/login"));
  }, [navigate]);

  useEffect(() => {
    const nombreCambio = nombre !== nombreInicial;
    const fotoCambio = foto !== null;
    setHasChanges(nombreCambio || fotoCambio);
  }, [nombre, nombreInicial, foto]);

  const handleFotoChange = (e) => {
    const file = e.target.files[0];
    setFoto(file);

    if (file) {
      setPreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrores({});

    const data = new FormData();
    data.append("nombre", nombre);
    if (foto) data.append("foto", foto);
    data.append("_method", "PUT");

    try {
      const res = await api.post("/user", data);
      setUser(res.data.user);

      setNombreInicial(res.data.user.nombre);
      setFotoInicial(res.data.user.foto_perfil);
      setFoto(null);

      if (res.data.user.foto_perfil) {
        setPreview(`http://localhost:8000/storage/${res.data.user.foto_perfil}`);
      }

      alert(t("profile.updated"));
    } catch (err) {
      if (err.response?.status === 422) {
        setErrores(err.response.data.errors);
      } else {
        alert(t("profile.error"));
      }
    }
  };

  if (!user) return <h2 className="loading-text">{t("loading")}</h2>;

  return (
    <div className="perfil-container">

      <button
        className="btn-custom btn-secondary-custom mb-3"
        onClick={() => navigate("/skins")}
      >
        ← {t("back_to_skins")}
      </button>

      <div className="perfil-header">
        <h2>{t("my_profile")}</h2>
      </div>

      <form onSubmit={handleSubmit} encType="multipart/form-data" className="perfil-form">

        <div className="form-group">

          {preview && (
            <div className="perfil-foto-container">
              <img
                src={preview}
                alt="Vista previa"
                width={150}
                className="perfil-foto mb-3"
                onError={(e) => {
                  e.target.style.display = "none";
                }}
              />
            </div>
          )}

          <div className="form-group form-group-file text-center">
            <label className="form-label form-label-subtle">
              {t("select_photo")}
            </label>

            <input
              type="file"
              className={`form-control file-input ${errores.foto ? "is-invalid" : ""}`}
              onChange={handleFotoChange}
            />

            {errores.foto && (
              <div className="invalid-feedback">
                {errores.foto.join(", ")}
              </div>
            )}
          </div>

          <label className="form-label">{t("name")}</label>

          <input
            type="text"
            className={`form-control ${errores.nombre ? "is-invalid" : ""}`}
            value={nombre}
            onChange={(e) => setNombre(e.target.value)}
            required
          />

          {errores.nombre && (
            <div className="invalid-feedback">
              {errores.nombre.join(", ")}
            </div>
          )}

        </div>

        <div className="text-center">
          <button
            type="submit"
            className="btn-custom btn-primary-custom"
            disabled={!hasChanges}
          >
            {t("save_changes")}
          </button>
        </div>
      </form>

      <div className="saldo-section">
        <h3>{t("balance")}</h3>

        <p className="saldo-amount">{user.saldo} €</p>

        <button
          className="btn-custom btn-success-custom"
          onClick={() => navigate("/recargar")}
        >
          {t("recharge")}
        </button>
      </div>
    </div>
  );
}

export default UserPerfil;