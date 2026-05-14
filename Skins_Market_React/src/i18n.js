import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import LanguageDetector from "i18next-browser-languagedetector";

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    fallbackLng: "es",
    lng: "es",
    interpolation: {
      escapeValue: false,
    },
    resources: {
      es: {
        translation: {
          "footer": {
            "title": "Skins Market",
            "rights": "Todos los derechos reservados",
            "back_to_skins": "Volver a skins",
            "favorites": "Favoritos",
            "profile": "Mi perfil",
            "top": "Ir arriba"
          },
          title: "Skins CS2",
          explore: "Explorar Skins",

          perfil: "Mi Perfil",
          quien_somos: "Quién somos",
          logout: "Salir",
          favoritos: "Favoritos",

          filters: "Filtros",
          search_name: "Buscar por nombre",
          search_placeholder: "Escribe el nombre de la skin",

          stickers: "Pegatinas",
          sticker_mode: "Modo de Pegatina",

          all: "Todos",
          with_stickers: "Con pegatinas",
          without_stickers: "Sin pegatinas",

          exterior: "Exterior",
          calidad: "Calidad",
          tipo: "Tipo",
          color: "Color",
          categoria: "Categoria",

          select_quality: "Seleccionar calidad",
          select_type: "Seleccionar tipo",
          select_category: "Seleccionar categoría",
          select_exterior: "Seleccionar exterior",
          select_color: "Seleccionar color",

          weapon: "Arma",
          gloves: "Guantes",
          agent: "Agente",

          min_price: "Precio mínimo",
          max_price: "Precio máximo",

          reset_filters: "Resetear filtros",

          available_skins: "Skins disponibles",
          loading: "Cargando...",
          no_results: "No se encontraron skins",

          view_skin: "Ver skin",
          view_sticker: "Ver pegatina",
          load_more: "Cargar más",

          ordenar: "Ordenar por",
          sort_none: "Sin ordenar",
          sort_name_asc: "Nombre (A-Z)",
          sort_name_desc: "Nombre (Z-A)",
          sort_price_asc: "Precio: menor a mayor",
          sort_price_desc: "Precio: mayor a menor",

          about: {
            hero: {
              badge: "MERCADO CS2 SKINS",
              title: "El Marketplace Definitivo para Skins de CS2",
              description:
                "Compra, vende e intercambia skins exclusivas de Counter-Strike 2 con total seguridad. Nuestra plataforma conecta a miles de jugadores alrededor del mundo ofreciendo precios competitivos y entregas instantáneas.",
              button: "Explorar Skins",
            },
            stats: {
              skins: "Skins Vendidas",
              users: "Usuarios Activos",
              security: "Transacciones Seguras",
              support: "Soporte Premium",
            },
            who: {
              tag: "QUIÉNES SOMOS",
              title:
                "Más que una tienda de skins, somos una comunidad gamer.",
              text1:
                "Nacimos con la idea de crear una plataforma moderna y segura para los amantes de Counter-Strike 2.",
              text2:
                "Trabajamos diariamente para ofrecer un marketplace estable y transparente.",
            },
            features: {
              tag: "POR QUÉ ELEGIRNOS",
              title: "Nuestra Plataforma",
              security: {
                title: "Seguridad Garantizada",
                text: "Transacciones protegidas con Steam.",
              },
              delivery: {
                title: "Entrega Instantánea",
                text: "Recibe skins en segundos.",
              },
              community: {
                title: "Comunidad Global",
                text: "Miles de jugadores activos.",
              },
              steam: {
                title: "Integración Steam",
                text: "Conexión directa con Steam.",
              },
            },
            map: {
              tag: "UBICACIÓN",
              title: "Nuestra Oficina",
              popup: "Institut Milà i Fontanals - Igualada",
            },
            "profile": {
                "my_profile": "Mi perfil",
                "back_to_skins": "Volver a Skins",
                "select_photo": "Seleccionar nueva foto",
                "name": "Nombre",
                "save_changes": "Guardar cambios",
                "balance": "Saldo",
                "recharge": "Recargar saldo",
                "updated": "Perfil actualizado correctamente",
                "error": "Error al actualizar el perfil"
              },
              "loading": "Cargando..."
          },
          "payment": {
            "title": "Recargar saldo",
            "amount": "Monto a recargar (EUR)",
            "loading": "Cargando formulario de pago...",
            "missing_key": "Falta configurar la clave de Stripe.",
            "load_error": "No se pudo cargar Stripe.",
            "browser_block": "Stripe fue bloqueado por el navegador."
          }
          
        },
        "back_to_skins": "Volver a skins",
        "in_favorites": "En favoritos",
        "add_favorite": "Agregar a favoritos",
        "loading_details": "Cargando detalles...",
        "not_found": "Skin no encontrada",
        "view_3d": "Ver en 3D",
        "no_3d_btn": "Sin 3D",
        "no_3d": "Esta skin no tiene modelo 3D",
        "filter_exterior": "Filtrar por exterior",
        "no_variants": "No hay variantes",
        "variants": "Variantes disponibles",
        "category": "Categoría",
        "view_details": "Ver detalles",
        "close": "Cerrar",
        
        "favorites_title": "Mis favoritos",
        "loading_favorites": "Cargando favoritos...",
        "no_favorites": "No tienes favoritos aún",
        "explore_skins": "Explorar skins",
        "back_to_skins": "Volver a skins",
        "profile": "Mi perfil",
        "favorites": "favoritos",
        "total": "Total",
        "view_details": "Ver detalles",
        "loading_sticker": "Cargando pegatina...",
        "sticker_not_found": "Pegatina no encontrada",
        "back": "Volver",
        "mode": "Modo",
        "view_skins": "Ver skins"
          
      },

      

      en: {
        translation: {
          "footer": {
            "title": "Skins Market",
            "rights": "All rights reserved",
            "back_to_skins": "Back to skins",
            "favorites": "Favorites",
            "profile": "My profile",
            "top": "Back to top"
          },
          title: "CS2 Skins",
          explore: "Browse Skins",

          perfil: "My Profile",
          quien_somos: "About",
          logout: "Logout",
          favoritos: "Favorites",

          filters: "Filters",
          search_name: "Search by name",
          search_placeholder: "Type skin name",

          stickers: "Stickers",
          sticker_mode: "Sticker Mode",

          all: "All",
          with_stickers: "With stickers",
          without_stickers: "Without stickers",

          exterior: "Exterior",
          calidad: "Quality",
          tipo: "Type",
          color: "Color",
          categoria: "Category",

          select_quality: "Select quality",
          select_type: "Select type",
          select_category: "Select category",
          select_exterior: "Select exterior",
          select_color: "Select color",

          weapon: "Weapon",
          gloves: "Gloves",
          agent: "Agent",

          min_price: "Min price",
          max_price: "Max price",

          reset_filters: "Reset filters",

          available_skins: "Available skins",
          loading: "Loading...",
          no_results: "No skins found",

          view_skin: "View skin",
          view_sticker: "View sticker",
          load_more: "Load more",

          ordenar: "Order by",
          sort_none: "No sorting",
          sort_name_asc: "Name (A-Z)",
          sort_name_desc: "Name (Z-A)",
          sort_price_asc: "Price: low to high",
          sort_price_desc: "Price: high to low",

          about: {
            hero: {
              badge: "CS2 SKINS MARKET",
              title: "The Ultimate CS2 Skins Marketplace",
              description:
                "Buy, sell and trade CS2 skins with full security and instant delivery.",
              button: "Browse Skins",
            },
            stats: {
              skins: "Skins Sold",
              users: "Active Users",
              security: "Secure Transactions",
              support: "Premium Support",
            },
            who: {
              tag: "WHO WE ARE",
              title: "More than a skins store, a gaming community.",
              text1:
                "We built a secure CS2 marketplace for players worldwide.",
              text2:
                "We ensure a stable and transparent trading experience.",
            },
            features: {
              tag: "WHY CHOOSE US",
              title: "Our Platform",
              security: {
                title: "Guaranteed Security",
                text: "Steam-protected transactions.",
              },
              delivery: {
                title: "Instant Delivery",
                text: "Fast automated delivery.",
              },
              community: {
                title: "Global Community",
                text: "Thousands of active users.",
              },
              steam: {
                title: "Steam Integration",
                text: "Direct Steam connection.",
              },
            },
            map: {
              tag: "LOCATION",
              title: "Our Office",
              popup: "Institut Milà i Fontanals - Igualada",
            },
            "profile": {
                "my_profile": "My Profile",
                "back_to_skins": "Back to Skins",
                "select_photo": "Select new photo",
                "name": "Name",
                "save_changes": "Save changes",
                "balance": "Balance",
                "recharge": "Add funds",
                "updated": "Profile updated successfully",
                "error": "Error updating profile"
              },
              "loading": "Loading..."
          },
          "payment": {
            "title": "Add funds",
            "amount": "Top-up amount (EUR)",
            "loading": "Loading payment form...",
            "missing_key": "Stripe key is missing.",
            "load_error": "Failed to load Stripe.",
            "browser_block": "Stripe was blocked by the browser."
          },
          "back_to_skins": "Back to skins",
          "in_favorites": "In favorites",
          "add_favorite": "Add to favorites",
        
          "loading_details": "Loading details...",
          "not_found": "Skin not found",
        
          "view_3d": "View in 3D",
          "no_3d_btn": "No 3D available",
          "no_3d": "This skin does not have a 3D model",
        
          "filter_exterior": "Filter by exterior",
          "no_variants": "No variants found",
          "variants": "Available variants",
        
          "category": "Category",
          "exterior": "Exterior",
        
          "view_details": "View details",
          "close": "Close",
        
          "stickers": "Stickers",
          "quality": "Quality",
          "favorites_title": "My Favorites",
          "loading_favorites": "Loading favorites...",
          "no_favorites": "You don’t have any favorites yet",
          "explore_skins": "Browse Skins",
          "back_to_skins": "Back to skins",
          "profile": "My Profile",
          "favorites": "favorites",
          "total": "Total",
          "view_details": "View details",
          "loading_sticker": "Loading sticker...",
          "sticker_not_found": "Sticker not found",
          "back": "Back",
          "mode": "Mode",
          "view_skins": "View Skins"
        },
        
      },
      
    },
    
  });

export default i18n;