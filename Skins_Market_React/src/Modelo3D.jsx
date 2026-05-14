import React, { Suspense, useRef, useState } from "react";
import { Canvas, useFrame } from "@react-three/fiber";
import { OrbitControls, useGLTF, Stage, Center, Html } from "@react-three/drei";

// --- SILENCIAR ADVERTENCIAS ESPECÍFICAS ---
const originalWarn = console.warn;
console.warn = (...args) => {
  if (args[0]?.includes?.('THREE.Clock')) return;
  originalWarn(...args);
};

function ModeloInterno({ modeloUrl, autoRotate }) {
  const { scene } = useGLTF(modeloUrl);
  const modelRef = useRef();

  // useFrame se ejecuta 60 veces por segundo
  useFrame(() => {
    if (modelRef.current && autoRotate) {
      // Girar sobre el eje Y
      modelRef.current.rotation.y += 0.005;
    }
  });

  return (
    <Center top>
      <primitive ref={modelRef} object={scene} scale={1.5} />
    </Center>
  );
}

export default function Visor3D({ modeloUrl, onClose }) {
  // Estado para controlar el giro automático
  const [isRotating, setIsRotating] = useState(true);

  return (
    <div style={{
      position: 'fixed',
      top: 0, left: 0,
      width: '100vw', height: '100vh',
      backgroundColor: 'rgba(0,0,0,0.95)',
      zIndex: 9999,
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      fontFamily: 'sans-serif'
    }}>
      
      {/* PANEL DE CONTROLES SUPERIOR */}
      <div style={{
        position: 'absolute',
        top: '30px',
        width: '100%',
        display: 'flex',
        justifyContent: 'center',
        gap: '20px',
        zIndex: 10001
      }}>
        {/* BOTÓN DE GIRO */}
        <button 
          onClick={() => setIsRotating(!isRotating)}
          style={{
            padding: '12px 24px',
            cursor: 'pointer',
            backgroundColor: isRotating ? '#4CAF50' : '#555',
            color: 'white',
            border: 'none',
            borderRadius: '8px',
            fontWeight: 'bold',
            transition: 'all 0.3s ease',
            boxShadow: '0 4px 15px rgba(0,0,0,0.3)'
          }}
        >
          {isRotating ? "⏸️ DETENER GIRO" : "▶️ ACTIVAR GIRO"}
        </button>

        {/* BOTÓN DE CIERRE */}
        <button 
          onClick={(e) => {
            e.stopPropagation();
            onClose();
          }}
          style={{
            padding: '12px 24px',
            cursor: 'pointer',
            backgroundColor: '#ff4d4d',
            color: 'white',
            border: 'none',
            borderRadius: '8px',
            fontWeight: 'bold',
            boxShadow: '0 4px 15px rgba(0,0,0,0.3)'
          }}
        >
          ✖️ CERRAR VISOR
        </button>
      </div>

      <div style={{ width: '90%', height: '85%', position: 'relative' }}>
        <Canvas camera={{ position: [0, 0, 5], fov: 45 }}>
          <color attach="background" args={['#050505']} />
          <ambientLight intensity={0.8} />
          
          <Suspense fallback={<Html center><div style={{color: 'white', fontSize: '1.2rem'}}>Cargando modelo...</div></Html>}>
            <Stage environment="city" intensity={0.5} contactShadow={false}>
              <ModeloInterno modeloUrl={modeloUrl} autoRotate={isRotating} />
            </Stage>
          </Suspense>

          {/* OrbitControls permite rotar, hacer zoom y mover el modelo con el ratón */}
          <OrbitControls 
            makeDefault 
            //Esto ayuda a que la cámara sea fluida
            enableDamping={true}
            dampingFactor={0.05}
          />
        </Canvas>
      </div>

      {/* PEQUEÑA AYUDA VISUAL */}
      <div style={{
        position: 'absolute',
        bottom: '20px',
        color: 'rgba(255,255,255,0.5)',
        fontSize: '0.9rem',
        pointerEvents: 'none'
      }}>
        Usa el ratón para rotar y la rueda para hacer zoom
      </div>
    </div>
  );
}