import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn, logout } from "../utils/auth";

function SkinDetail() {
  const { id } = useParams(); // id de la skin
  const [item, setItem] = useState(null);
  const [exteriores, setExteriores] = useState([]);
  const [armasFiltradas, setArmasFiltradas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!isLoggedIn()) {
      window.location.href = "/login";
      return;
    }

    // Traer detalle de la skin
    api.get(`/skins/${id}`)
      .then(res => {
        setItem(res.data.item);
        setExteriores(res.data.exteriores);
      })
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  }, [id]);

  // Filtrar por exterior
  const filtrarExterior = (exteriorId) => {
    api.get(`/skins/${id}/exterior/${exteriorId}`)
      .then(res => setArmasFiltradas(res.data.armas))
      .catch(err => console.error(err));
  };

  if (loading) return <p>Cargando...</p>;
  if (!item) return <p>Skin no encontrada</p>;

  const firstArma = item.armas?.[0];

  return (
    <div className="container mt-5">
        <h2>{item.nombre}</h2>
        <h4>Precio: ${item.precio}</h4>
        <p>Calidad: {item.calidad}</p>
        {item.armas?.[0]?.exterior && (
        <p>Exterior: {item.armas[0].exterior.nombre}</p>
        )}
        <img
        src={item.foto ? `http://localhost:8000/storage/${item.foto}` : 'https://via.placeholder.com/150'}
        alt={item.nombre}
        style={{ maxWidth: "200px" }}
        />

      {/* Botones por exterior */}
      <div className="mt-3">
        <h5>Filtrar por Exterior:</h5>
        {exteriores.map(ext => (
          <button
            key={ext.id}
            className="btn btn-outline-primary me-2 mb-2"
            onClick={() => filtrarExterior(ext.id)}
          >
            {ext.nombre}
          </button>
        ))}
      </div>

      {/* Pegatinas */}
      {firstArma?.pegatinas?.length > 0 && (
        <div className="mt-3">
          <h5>Pegatinas:</h5>
          {firstArma.pegatinas.map((p, i) => (
            <span key={i} className="badge bg-warning me-1">{p.modoPegatina.nombre}</span>
          ))}
        </div>
      )}

      {/* Armas filtradas por exterior */}
      {armasFiltradas.length > 0 && (
        <div className="row mt-4">
          {armasFiltradas.map((arma, idx) => (
            <div key={idx} className="col-md-4 mb-4">
              <div className="card">
                <img
                  src={arma.item.foto ? `http://localhost:8000/storage/${arma.item.foto}` : 'https://via.placeholder.com/150'}
                  alt={arma.item.nombre}
                  className="card-img-top"
                />
                <div className="card-body">
                  <h5>{arma.item.nombre}</h5>
                  <p>Precio: ${arma.item.precio}</p>
                  <p>Categoría: {arma.categoria.nombre}</p>
                  <p>Exterior: {arma.exterior.nombre}</p>

                  {arma.pegatinas.length > 0 && (
                    <div>
                      {arma.pegatinas.map((p, i) => (
                        <span key={i} className="badge bg-warning me-1">{p.modoPegatina.nombre}</span>
                      ))}
                    </div>
                  )}

                  <button className="btn btn-success mt-2">Comprar</button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default SkinDetail;
