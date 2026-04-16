import { useStripe, useElements, CardElement } from "@stripe/react-stripe-js";
import { useState } from "react";
import api from "../api/api";

const CheckoutForm = ({ monto, onSuccess }) => {
  const stripe = useStripe();
  const elements = useElements();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!stripe || !elements) return;

    setLoading(true);
    setError(null);

    try {
      // 1. Llamamos a tu StripeController.php para obtener el clientSecret
      const { data } = await api.post("/depositar", { amount: monto });
      const clientSecret = data.clientSecret;

      // 2. Confirmamos el pago con la tarjeta introducida en el CardElement
      const result = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: elements.getElement(CardElement),
        },
      });

      if (result.error) {
        setError(result.error.message);
      } else {
        if (result.paymentIntent.status === "succeeded") {
          alert("¡Pago realizado con éxito!");
          onSuccess(); // Función para refrescar el saldo en el perfil
        }
      }
    } catch (err) {
      setError("Error al conectar con el servidor");
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="payment-form">
      <div style={{ padding: "10px", border: "1px solid #ccc", borderRadius: "5px", background: "white", marginBottom: "15px" }}>
        <CardElement options={{
          style: {
            base: { fontSize: "16px", color: "#424770", "::placeholder": { color: "#aab7c4" } },
            invalid: { color: "#9e2146" },
          },
        }} />
      </div>
      {error && <div style={{ color: "red", marginBottom: "10px" }}>{error}</div>}
      <button className="btn-custom btn-success-custom" disabled={!stripe || loading}>
        {loading ? "Procesando..." : `Pagar ${monto} €`}
      </button>
    </form>
  );
};

export default CheckoutForm;