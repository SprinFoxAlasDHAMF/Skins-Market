import { MapContainer, TileLayer, Marker, Popup } from "react-leaflet";
import { FaShieldAlt, FaUsers, FaRocket, FaSteam } from "react-icons/fa";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import "leaflet/dist/leaflet.css";
import "../styles/About.css";

export default function About() {
  const position = [41.5807, 1.6177];
  const navigate = useNavigate();
  const { t } = useTranslation();

  const stats = [
    { title: "+25.000", subtitle: t("about.stats.skins") },
    { title: "+12.000", subtitle: t("about.stats.users") },
    { title: "99.8%", subtitle: t("about.stats.security") },
    { title: "24/7", subtitle: t("about.stats.support") },
  ];

  const features = [
    {
      icon: <FaShieldAlt />,
      title: t("about.features.security.title"),
      text: t("about.features.security.text"),
    },
    {
      icon: <FaRocket />,
      title: t("about.features.delivery.title"),
      text: t("about.features.delivery.text"),
    },
    {
      icon: <FaUsers />,
      title: t("about.features.community.title"),
      text: t("about.features.community.text"),
    },
    {
      icon: <FaSteam />,
      title: t("about.features.steam.title"),
      text: t("about.features.steam.text"),
    },
  ];

  return (
    <div className="about-container">

      {/* HERO */}
      <section className="about-hero">
        <div className="hero-overlay"></div>

        <div className="about-hero-content">
          <span className="hero-badge">
            {t("about.hero.badge")}
          </span>

          <h1>
            {t("about.hero.title")}
          </h1>

          <p>
            {t("about.hero.description")}
          </p>

          <div className="hero-buttons">
            <button
              className="btn-custom btn-primary-custom"
              onClick={() => navigate("/skins")}
            >
              {t("about.hero.button")}
            </button>
          </div>
        </div>
      </section>

      {/* STATS */}
      <section className="about-stats">
        {stats.map((stat, index) => (
          <div className="stat-card" key={index}>
            <h2>{stat.title}</h2>
            <p>{stat.subtitle}</p>
          </div>
        ))}
      </section>

      {/* ABOUT */}
      <section className="about-section">
        <div className="about-left">
          <span className="section-tag">
            {t("about.who.tag")}
          </span>

          <h2>
            {t("about.who.title")}
          </h2>

          <p>
            {t("about.who.text1")}
          </p>

          <p>
            {t("about.who.text2")}
          </p>
        </div>

        <div className="about-right">
          <img
            src="https://images.unsplash.com/photo-1542751371-adc38448a05e"
            alt="CS2"
          />
        </div>
      </section>

      {/* FEATURES */}
      <section className="features-section">
        <div className="section-header">
          <span className="section-tag">
            {t("about.features.tag")}
          </span>
          <h2>
            {t("about.features.title")}
          </h2>
        </div>

        <div className="features-grid">
          {features.map((feature, index) => (
            <div className="feature-card" key={index}>
              <div className="feature-icon">{feature.icon}</div>
              <h3>{feature.title}</h3>
              <p>{feature.text}</p>
            </div>
          ))}
        </div>
      </section>

      {/* MAP */}
      <section className="map-section">
        <div className="section-header">
          <span className="section-tag">
            {t("about.map.tag")}
          </span>
          <h2>
            {t("about.map.title")}
          </h2>
        </div>

        <div className="map-wrapper">
          <MapContainer
            center={position}
            zoom={16}
            scrollWheelZoom={false}
            style={{ height: "450px", width: "100%" }}
          >
            <TileLayer
              attribution='&copy; OpenStreetMap contributors'
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />

            <Marker position={position}>
              <Popup>
                {t("about.map.popup")}
              </Popup>
            </Marker>
          </MapContainer>
        </div>
      </section>
    </div>
  );
}