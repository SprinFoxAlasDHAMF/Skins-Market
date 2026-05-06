import { useState } from "react";
import api from "../api/api";
import { useNavigate, Link } from "react-router-dom";

function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");

  const [password, setPassword] = useState("");
  const [passwordConfirm, setPasswordConfirm] = useState("");

  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirm, setShowPasswordConfirm] = useState(false);

  const [passwordStrength, setPasswordStrength] = useState(0);
  const [capsLock, setCapsLock] = useState(false);

  const navigate = useNavigate();

  const blockClipboard = (e) => e.preventDefault();

  const handleKeyCheck = (e) => {
    setCapsLock(e.getModifierState && e.getModifierState("CapsLock"));
  };

  const checkPasswordStrength = (pass) => {
    let strength = 0;

    if (pass.length >= 8) strength++;
    if (/[A-Z]/.test(pass)) strength++;
    if (/[a-z]/.test(pass)) strength++;
    if (/[0-9]/.test(pass)) strength++;
    if (/[^A-Za-z0-9]/.test(pass)) strength++;

    setPasswordStrength(strength);
  };

  const handleRegister = async (e) => {
    e.preventDefault();

    // ❌ bloqueo contraseña débil
    if (passwordStrength < 5) {
      alert("La contraseña no es lo suficientemente segura");
      return;
    }

    // ❌ bloqueo si no coinciden
    if (password !== passwordConfirm) {
      alert("Las contraseñas no coinciden");
      return;
    }

    try {
      await api.post("/register", {
        name,
        email,
        password,
        password_confirmation: passwordConfirm,
      });

      alert("Usuario creado. Ahora inicia sesión.");
      navigate("/login");
    } catch (error) {
      alert("Error al registrarse");
    }
  };

  return (
    <div>
      <h1>Registro</h1>

      <form onSubmit={handleRegister}>
        <input
          type="text"
          placeholder="Nombre"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        <br /><br />

        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <br /><br />

        {/* PASSWORD */}
        <div style={{ position: "relative", display: "inline-block" }}>
          <input
            type={showPassword ? "text" : "password"}
            placeholder="Contraseña"
            value={password}
            onChange={(e) => {
              setPassword(e.target.value);
              checkPasswordStrength(e.target.value);
            }}
            onKeyDown={handleKeyCheck}
            onKeyUp={handleKeyCheck}
            onCopy={blockClipboard}
            onPaste={blockClipboard}
            onCut={blockClipboard}
          />

          <span
            onClick={() => setShowPassword(!showPassword)}
            style={{
              position: "absolute",
              right: "8px",
              top: "50%",
              transform: "translateY(-50%)",
              cursor: "pointer",
            }}
          >
            {showPassword ? "🔓" : "🔒"}
          </span>
        </div>

        <br /><br />

        {/* CONFIRM PASSWORD */}
        <div style={{ position: "relative", display: "inline-block" }}>
          <input
            type={showPasswordConfirm ? "text" : "password"}
            placeholder="Confirmar contraseña"
            value={passwordConfirm}
            onChange={(e) => setPasswordConfirm(e.target.value)}
            onKeyDown={handleKeyCheck}
            onKeyUp={handleKeyCheck}
            onCopy={blockClipboard}
            onPaste={blockClipboard}
            onCut={blockClipboard}
          />

          <span
            onClick={() => setShowPasswordConfirm(!showPasswordConfirm)}
            style={{
              position: "absolute",
              right: "8px",
              top: "50%",
              transform: "translateY(-50%)",
              cursor: "pointer",
            }}
          >
            {showPasswordConfirm ? "🔓" : "🔒"}
          </span>
        </div>

        {/* BARRA SEGURIDAD */}
        <div style={{ marginTop: "10px", height: "8px", background: "#ddd" }}>
          <div
            style={{
              width: `${(passwordStrength / 5) * 100}%`,
              height: "100%",
              transition: "0.3s",
              background:
                passwordStrength <= 2
                  ? "red"
                  : passwordStrength <= 4
                  ? "orange"
                  : "green",
            }}
          />
        </div>

        <small>
          Seguridad de contraseña:{" "}
          {passwordStrength <= 2
            ? "Débil"
            : passwordStrength <= 4
            ? "Media"
            : "Fuerte"}
        </small>

        <br /><br />

        {capsLock && (
          <small style={{ color: "orange" }}>
            ⚠️ Bloq Mayús activado
          </small>
        )}

        <br /><br />

        <button type="submit">Registrarse</button>
      </form>

      <p>
        ¿Ya tienes cuenta? <Link to="/login">Inicia sesión</Link>
      </p>
    </div>
  );
}

export default Register;