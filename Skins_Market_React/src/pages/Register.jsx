import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import api from "../api/api";

function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const blockClipboard = (e) => e.preventDefault();

  const [password, setPassword] = useState("");
  const [passwordConfirm, setPasswordConfirm] = useState("");
  const [error, setError] = useState("");

  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirm, setShowPasswordConfirm] = useState(false);

  const [capsLock, setCapsLock] = useState(false);

  const navigate = useNavigate();

  //CHECKS PASSWORD
  const checks = {
    length: password.length >=5,
    upper: /[A-Z]/.test(password),
    lower: /[a-z]/.test(password),
    number: /[0-9]/.test(password),
    special: /[^A-Za-z0-9]/.test(password),
  };

  const strength = Object.values(checks).filter(Boolean).length;

  const handleRegister = async (e) => {
    e.preventDefault();
    setError("");

    if (password !== passwordConfirm) {
      setError("Las contraseñas no coinciden");
      return;
    }

    if (strength < 5) {
      setError("La contraseña es demasiado débil");
      return;
    }

    try {
      await api.post("/register", {
        name,
        email,
        password,
        password_confirmation: passwordConfirm,
      });

      navigate("/login");
    } catch (err) {
      console.log(err.response.data);
      setError("Error al registrarse");
    }
  };

  const handleKey = (e) => {
    setCapsLock(e.getModifierState && e.getModifierState("CapsLock"));
  };

  return (
    <div className="container mt-5" style={{ maxWidth: "400px" }}>
      <h2>Registro</h2>

      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleRegister}>

        {/* NAME */}
        <div className="mb-3">
          <label>Nombre</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
          />
        </div>

        {/* EMAIL */}
        <div className="mb-3">
          <label>Email</label>
          <input
            type="email"
            className="form-control"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        {/* PASSWORD */}
        <div className="mb-3">
          <label>Contraseña</label>

          <div style={{ position: "relative" }}>
          <input
            type={showPassword ? "text" : "password"}
            className="form-control"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            onKeyDown={handleKey}
            onKeyUp={handleKey}
            onCopy={blockClipboard}
            onPaste={blockClipboard}
            onCut={blockClipboard}
            required
          />

            <span
              onClick={() => setShowPassword(!showPassword)}
              style={{
                position: "absolute",
                right: "10px",
                top: "50%",
                transform: "translateY(-50%)",
                cursor: "pointer",
              }}
            >
              {showPassword ? "🔓" : "🔒"}
            </span>
          </div>

          {/* 🔥 BARRA SEGURIDAD */}
          <div className="mt-2" style={{ height: "6px", background: "#ddd" }}>
            <div
              style={{
                width: `${(strength / 5) * 100}%`,
                height: "100%",
                transition: "0.3s",
                background:
                  strength <= 2
                    ? "red"
                    : strength <= 4
                    ? "orange"
                    : "green",
              }}
            />
          </div>

          {/* ✔ CHECKS */}
          <small>
            <div style={{ color: checks.length ? "green" : "#999" }}>
              ✔ 5 caracteres
            </div>
            <div style={{ color: checks.upper ? "green" : "#999" }}>
              ✔ Mayúscula
            </div>
            <div style={{ color: checks.lower ? "green" : "#999" }}>
              ✔ Minúscula
            </div>
            <div style={{ color: checks.number ? "green" : "#999" }}>
              ✔ Número
            </div>
            <div style={{ color: checks.special ? "green" : "#999" }}>
              ✔ Símbolo
            </div>
          </small>
        </div>

        {/* CONFIRM PASSWORD */}
        <div className="mb-3">
          <label>Confirmar contraseña</label>

          <div style={{ position: "relative" }}>
            <input
              type={showPasswordConfirm ? "text" : "password"}
              className="form-control"
              value={passwordConfirm}
              onChange={(e) => setPasswordConfirm(e.target.value)}
              onKeyDown={handleKey}
              onKeyUp={handleKey}
              onCopy={blockClipboard}
              onPaste={blockClipboard}
              onCut={blockClipboard}
              required
            />

            <span
              onClick={() => setShowPasswordConfirm(!showPasswordConfirm)}
              style={{
                position: "absolute",
                right: "10px",
                top: "50%",
                transform: "translateY(-50%)",
                cursor: "pointer",
              }}
            >
              {showPasswordConfirm ? "🔓" : "🔒"}
            </span>
          </div>

          {capsLock && (
            <small style={{ color: "orange" }}>
              ⚠️ Bloq Mayús activado
            </small>
          )}
        </div>

        <button
          type="submit"
          className="btn btn-primary w-100"
          disabled={strength < 5}
        >
          Registrarse
        </button>
      </form>

      <p className="mt-3 text-center">
        ¿Ya tienes cuenta? <Link to="/login">Inicia sesión</Link>
      </p>
    </div>
  );
}

export default Register;