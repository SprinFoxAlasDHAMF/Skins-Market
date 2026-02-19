import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn } from "../utils/auth";

function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  // Si el usuario ya está logueado, redirige a skins
  if (isLoggedIn()) {
    navigate("/skins");
  }

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");

    try {
      // Petición al backend
      const res = await api.post("/login", { email, password });

      // Guardar token
      localStorage.setItem("token", res.data.token);

      // Guardar usuario
      localStorage.setItem("user", JSON.stringify(res.data.user));

      // Redirigir a skins
      navigate("/skins");
    } catch (err) {
      console.error(err);
      setError("Credenciales incorrectas. Intenta de nuevo.");
    }
  };

  return (
    <div className="container mt-5" style={{ maxWidth: "400px" }}>
      <h2>Login</h2>

      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleLogin}>
        <div className="mb-3">
          <label>Email</label>
          <input
            type="email"
            className="form-control"
            placeholder="Tu email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label>Contraseña</label>
          <input
            type="password"
            className="form-control"
            placeholder="Contraseña"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>

        <button type="submit" className="btn btn-primary w-100">Entrar</button>
      </form>

      <p className="mt-3 text-center">
        ¿No tienes cuenta? <Link to="/register">Regístrate aquí</Link>
      </p>
    </div>
  );
}

export default Login;
