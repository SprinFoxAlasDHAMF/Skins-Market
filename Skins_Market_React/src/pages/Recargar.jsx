import { loadStripe } from "@stripe/stripe-js";
import { Elements} from "@stripe/react-stripe-js";
import CheckoutForm from "./CheckoutForm";
import { useState } from "react";


// Sustituye con tu Clave Pública de Stripe (pk_test_...)
const stripePromise = loadStripe("pk_test_51SeyzmJCrp6PnkD4YcsvTjWMW33NTsUZjPX8dPT0Tc4XEWsDbPRkmrY4n0t94fMWIvToyh724CsjBlkkoxk56PpN00I1HOw52l");

function Recargar() {
  const [monto, setMonto] = useState(10);

  return (
    <div className="perfil-container">
      <h2>Recargar Saldo</h2>
      <div className="mb-3">
        <label className="form-label">Monto a recargar (€)</label>
        <input 
          type="number" 
          className="form-control"
          value={monto} 
          onChange={(e) => setMonto(e.target.value)} 
        />
      </div>

      <Elements stripe={stripePromise}>
        <CheckoutForm monto={monto} onSuccess={() => window.location.href = "/perfil"} />
      </Elements>
    </div>
  );
}

export default Recargar;