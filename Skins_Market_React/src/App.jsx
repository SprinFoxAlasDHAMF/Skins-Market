import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Skins from "./pages/Skins";
import Login from "./pages/Login";
import Register from "./pages/Register";
import SkinDetail from "./pages/SkinDetail";
import AdminForm from "./pages/AdminForm";
import UserPerfil from "./pages/UserPerfil";
import Favoritos from "./pages/Favoritos";
import Recargar from "./pages/Recargar";
import PegatinaDetail from "./pages/PegatinaDetail";
import About from './pages/About';
function App() {
  return (
    <BrowserRouter>
      <Routes>

        <Route path="/" element={<Navigate to="/login" />} />

        <Route path="/skins" element={<Skins />} />
        <Route path="/skins/:id" element={<SkinDetail />} />

        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/perfil" element={<UserPerfil />} />
        <Route path="/favoritos" element={<Favoritos />} />

        <Route path="/admin/skins/new" element={<AdminForm />} />
        <Route path="/admin/skins/:id/edit" element={<AdminForm />} />

        <Route path="/recargar" element={<Recargar />} />

        <Route path="/pegatinas/:id" element={<PegatinaDetail />} />

        <Route path="/qui-som" element={<About />} />

      </Routes>
    </BrowserRouter>
  );
}

export default App;