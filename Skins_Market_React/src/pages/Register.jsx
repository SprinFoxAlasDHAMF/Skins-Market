import { useState } from "react";
import api from "../api/api";
import { useNavigate, Link } from "react-router-dom";

function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirm, setPasswordConfirm] = useState("");
  const navigate = useNavigate();

  const handleRegister = async (e) => {
    e.preventDefault();
    try {
      await api.post("/register", {
        name,
        email,
        password,
        password_confirmation: passwordConfirm,
      });

      alert("Usuario creado. Ahora inicia sesión.");
      navigate("/login"); // redirige a login tras registro
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
        <input
          type="password"
          placeholder="Contraseña"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <br /><br />
        <input
          type="password"
          placeholder="Confirmar contraseña"
          value={passwordConfirm}
          onChange={(e) => setPasswordConfirm(e.target.value)}
        />
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
