import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import api from "../api/api";
import { isLoggedIn } from "../utils/auth";

function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const [capsLock, setCapsLock] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const navigate = useNavigate();

  if (isLoggedIn()) {
    navigate("/skins");
  }

  const handleLogin = async (e) => {
    e.preventDefault();
    
    setError("");

    try {
      const res = await api.post("/login", { email, password });

      localStorage.setItem("token", res.data.token);
      localStorage.setItem("user", JSON.stringify(res.data.user));

      navigate("/skins");
    } catch (err) {
      console.error(err);
      setError("Credenciales incorrectas. Intenta de nuevo.");
    }
  };

  // Detectar Caps Lock
  const handleKeyDown = (e) => {
    setCapsLock(e.getModifierState && e.getModifierState("CapsLock"));
  };

  const handleKeyUp = (e) => {
    setCapsLock(e.getModifierState && e.getModifierState("CapsLock"));
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

          <div style={{ position: "relative" }}>
            <input
              type={showPassword ? "text" : "password"}
              className="form-control"
              placeholder="Contraseña"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              onKeyDown={handleKeyDown}
              onKeyUp={handleKeyUp}
              required
            />

            {/* Botón ojo */}
            <span
              onClick={() => setShowPassword(!showPassword)}
              style={{
                position: "absolute",
                right: "10px",
                top: "50%",
                transform: "translateY(-50%)",
                cursor: "pointer",
                userSelect: "none",
              }}
            >
              {showPassword ? "🔓" : "🔒"}
            </span>
          </div>

          {/* Aviso Caps Lock */}
          {capsLock && (
            <small style={{ color: "orange" }}>
              ⚠️ Bloq Mayús activado
            </small>
          )}
        </div>

        <button type="submit" className="btn btn-primary w-100">
          Entrar
        </button>
      </form>

      <p className="mt-3 text-center">
        ¿No tienes cuenta? <Link to="/register">Regístrate aquí</Link>
      </p>
    </div>
  );
}

export default Login;