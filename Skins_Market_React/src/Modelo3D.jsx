import React, { Suspense, useRef } from "react";
import { Canvas, useFrame } from "@react-three/fiber";
import { OrbitControls, useGLTF, Stage, Center, Html } from "@react-three/drei";

// --- SILENCIAR ADVERTENCIAS ESPECÍFICAS (Opcional pero recomendado) ---
const originalWarn = console.warn;
console.warn = (...args) => {
  if (args[0]?.includes?.('THREE.Clock')) return;
  originalWarn(...args);
};

function ModeloInterno({ modeloUrl }) {
  const { scene } = useGLTF(modeloUrl);
  const modelRef = useRef();

  useFrame((state) => {
    if (modelRef.current) {
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
  return (
    <div style={{
      position: 'fixed',
      top: 0, left: 0,
      width: '100vw', height: '100vh',
      backgroundColor: 'rgba(0,0,0,0.9)',
      zIndex: 9999, // Capa base del modal
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center'
    }}>
      {/* BOTÓN DE CIERRE: Esencial que tenga zIndex superior al Canvas */}
      <button 
        onClick={(e) => {
          e.stopPropagation(); // Evita que el clic pase al 3D
          onClose();
        }}
        style={{
          position: 'absolute',
          top: '40px',
          right: '40px',
          zIndex: 10001, // Por encima de todo
          padding: '10px 20px',
          cursor: 'pointer',
          backgroundColor: '#ff4d4d',
          color: 'white',
          border: 'none',
          borderRadius: '5px',
          fontWeight: 'bold'
        }}
      >
        ✖️ CERRAR VISOR
      </button>

      <div style={{ width: '80%', height: '80%', position: 'relative' }}>
        <Canvas camera={{ position: [0, 0, 5], fov: 45 }}>
          <color attach="background" args={['#111']} />
          <ambientLight intensity={0.8} />
          <Suspense fallback={<Html center><div style={{color: 'white'}}>Cargando...</div></Html>}>
            <Stage environment="city" intensity={0.6} contactShadow={false}>
              <ModeloInterno modeloUrl={modeloUrl} />
            </Stage>
          </Suspense>
          <OrbitControls makeDefault />
        </Canvas>
      </div>
    </div>
  );
}