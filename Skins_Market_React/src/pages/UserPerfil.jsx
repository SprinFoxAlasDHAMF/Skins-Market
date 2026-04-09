import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import "../styles/UserPerfil.css";

function UserPerfil() {
  const navigate = useNavigate();
  const [nombre, setNombre] = useState("");
  const [foto, setFoto] = useState(null);
  const [preview, setPreview] = useState(null); // Estado para previsualización
  const [user, setUser] = useState(null);
  const [errores, setErrores] = useState({}); // Para errores de validación

  // Cargar datos del usuario
  useEffect(() => {
    api.get("/user")
      .then(res => {
        console.log("Datos del usuario:", res.data);
        console.log("Foto perfil:", res.data.foto_perfil);
        setUser(res.data);
        setNombre(res.data.nombre);
        if (res.data.foto_perfil) {
          const fotoUrl = `http://localhost:8000/storage/${res.data.foto_perfil}`;
          console.log("URL de la foto:", fotoUrl);
          setPreview(fotoUrl);
        } else {
          console.log("No hay foto de perfil guardada");
        }
      })
      .catch(() => navigate("/login"));
  }, [navigate]);

  // Actualizar preview cuando el usuario selecciona una foto nueva
  const handleFotoChange = (e) => {
    const file = e.target.files[0];
    setFoto(file);
    if (file) {
      setPreview(URL.createObjectURL(file));
    } else {
      setPreview(user?.foto_perfil 
        ? `http://localhost:8000/storage/${user.foto_perfil}` 
        : null
      );
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrores({}); // Limpiar errores previos

    const data = new FormData();
    data.append("nombre", nombre);
    if (foto) data.append("foto", foto);
    data.append("_method", "PUT"); // Laravel interpreta como PUT

    try {
      const res = await api.post("/user", data);
      setUser(res.data.user);
      alert("Perfil actualizado ✅");

      // Actualizar preview si cambió la foto
      if (res.data.user.foto_perfil) {
        setPreview(`http://localhost:8000/storage/${res.data.user.foto_perfil}`);
      }
      setFoto(null); // Limpiar archivo seleccionado
    } catch (err) {
      if (err.response?.status === 422) {
        setErrores(err.response.data.errors);
      } else {
        console.error(err);
        alert("Error al actualizar el perfil");
      }
    }
  };

  if (!user) return <h2 className="loading-text">Cargando...</h2>;

  return (
    <div className="perfil-container">
      <button className="btn-custom btn-secondary-custom mb-3" onClick={() => navigate("/skins")}>
          ← Volver a Skins
        </button>
      <div className="perfil-header">
        <h2>Mi perfil</h2>
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
              console.error("Error cargando imagen:", preview);
              e.target.style.display = 'none';
            }}
          />
          
          <div className="form-group form-group-file text-center">
            <label className="form-label form-label-subtle">Seleccionar nueva foto</label>
            <input
              type="file"
              className={`form-control file-input ${errores.foto ? 'is-invalid' : ''}`}
              onChange={handleFotoChange}
            />
            {errores.foto && (
              <div className="invalid-feedback">
                {errores.foto.join(", ")}
              </div>
            )}
          </div>
        </div>
      )}
      <label className="form-label">Nombre</label>
          <input
            type="text"
            className={`form-control ${errores.nombre ? 'is-invalid' : ''}`}
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
          <button type="submit" className="btn-custom btn-primary-custom">Guardar cambios</button>
        </div>
      </form>

      <div className="saldo-section">
        <h3>Saldo</h3>
        <p className="saldo-amount">{user.saldo} €</p>
        <button className="btn-custom btn-success-custom" onClick={() => navigate("/recargar")}>
          Recargar saldo
        </button>
      </div>
    </div>
  );
}

export default UserPerfil;
