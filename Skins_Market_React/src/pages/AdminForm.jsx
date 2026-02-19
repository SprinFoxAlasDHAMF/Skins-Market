import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import api from "../api/api";

function AdminForm() {
  const navigate = useNavigate();
  const { id } = useParams(); // si hay id → editar
  const isEdit = !!id;

  const [loading, setLoading] = useState(false);

  const [calidades, setCalidades] = useState([]);
  const [categorias, setCategorias] = useState([]);
  const [exteriores, setExteriores] = useState([]);

  const [form, setForm] = useState({
    nombre: "",
    precio: "",
    tipo: "arma",
    calidad_id: "",
    categoria_id: "",
    exterior_id: "",
    color: "",
    foto: null,
  });

  const [preview, setPreview] = useState(null);

  // =============================
  // Cargar filtros
  // =============================
  useEffect(() => {
    api.get("/filters")
      .then(res => {
        setCalidades(res.data.calidades);
        setCategorias(res.data.categorias);
        setExteriores(res.data.exteriores);
      })
      .catch(err => console.error(err));
  }, []);

  // =============================
  // Cargar skin si es edición
  // =============================
  useEffect(() => {
    if (!isEdit) return;

    api.get(`/admin/skins/${id}`)
      .then(res => {
        setForm({
          nombre: res.data.nombre,
          precio: res.data.precio,
          tipo: res.data.tipo,
          calidad_id: res.data.calidad_id ?? "",
          categoria_id: res.data.categoria_id ?? "",
          exterior_id: res.data.exterior_id ?? "",
          color: res.data.color ?? "",
          foto: null,
        });

        if (res.data.foto) {
          setPreview(`http://localhost:8000/storage/${res.data.foto}`);
        }
      })
      .catch(() => alert("Error cargando skin"));
  }, [id]);

  // =============================
  // Manejar inputs
  // =============================
  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleFile = e => {
    setForm({ ...form, foto: e.target.files[0] });
    setPreview(URL.createObjectURL(e.target.files[0]));
  };

  // =============================
  // Submit
  // =============================
  const handleSubmit = async e => {
    e.preventDefault();
    setLoading(true);

    try {
      const data = new FormData();

      // Convertimos "" a null para evitar errores en Laravel
      Object.keys(form).forEach(key => {
        let value = form[key];
        if (value === "") value = null;
        if (value !== null) data.append(key, value);
      });

      if (isEdit) {
        // PUT vía POST
        data.append("_method", "PUT");
        await api.post(`/admin/skins/${id}`, data);
      } else {
        await api.post("/admin/skins", data);
      }

      alert(`Skin ${isEdit ? "actualizada" : "creada"} correctamente`);
      navigate("/skins");
    } catch (err) {
      console.error(err);
      alert("Error guardando skin");
    } finally {
      setLoading(false);
    }
  };

  // =============================
  // UI
  // =============================
  return (
    <div className="container mt-5">
      <h2>{isEdit ? "Editar Skin" : "Crear Skin"}</h2>

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        <div className="mb-3">
          <label>Nombre</label>
          <input
            name="nombre"
            value={form.nombre}
            onChange={handleChange}
            className="form-control"
            required
          />
        </div>

        <div className="mb-3">
          <label>Precio</label>
          <input
            type="number"
            name="precio"
            value={form.precio}
            onChange={handleChange}
            className="form-control"
            required
          />
        </div>

        <div className="mb-3">
          <label>Tipo</label>
          <select
            name="tipo"
            value={form.tipo}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="arma">Arma</option>
            <option value="guantes">Guantes</option>
            <option value="agente">Agente</option>
            <option value="llavero">Llavero</option>
          </select>
        </div>

        <div className="mb-3">
          <label>Calidad</label>
          <select
            name="calidad_id"
            value={form.calidad_id}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="">Seleccionar</option>
            {calidades.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
          </select>
        </div>

        <div className="mb-3">
          <label>Categoría</label>
          <select
            name="categoria_id"
            value={form.categoria_id}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="">Seleccionar</option>
            {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
          </select>
        </div>

        <div className="mb-3">
          <label>Exterior</label>
          <select
            name="exterior_id"
            value={form.exterior_id}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="">Seleccionar</option>
            {exteriores.map(e => <option key={e.id} value={e.id}>{e.nombre}</option>)}
          </select>
        </div>

        <div className="mb-3">
          <label>Color</label>
          <input
            name="color"
            value={form.color}
            onChange={handleChange}
            className="form-control"
          />
        </div>

        <div className="mb-3">
          <label>Imagen</label>
          <input
            type="file"
            onChange={handleFile}
            className="form-control"
          />
        </div>

        {preview && (
          <img
            src={preview}
            alt="preview"
            style={{ width: 150, marginBottom: 20 }}
          />
        )}

        <button className="btn btn-success" disabled={loading}>
          {loading ? "Guardando..." : isEdit ? "Actualizar" : "Crear"}
        </button>

        <button
          type="button"
          className="btn btn-secondary ms-2"
          onClick={() => navigate("/skins")}
        >
          Cancelar
        </button>
      </form>
    </div>
  );
}

export default AdminForm;
