import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
  // Carrega variáveis de ambiente
  const env = loadEnv(mode, process.cwd(), '');

  // Configuração do servidor baseada no ambiente
  const serverConfig = {
    host: '0.0.0.0',
    port: 5173,
  };

  // Se VITE_HMR_HOST estiver definido, usa para HMR (desenvolvimento remoto WSL)
  // Caso contrário, usa localhost (desenvolvimento local)
  if (env.VITE_HMR_HOST) {
    serverConfig.hmr = {
      host: env.VITE_HMR_HOST,
    };
  }

  return {
    plugins: [
      tailwindcss(),
      laravel({
        input: ["resources/css/app.css", "resources/js/app.js"],
        refresh: true,
      }),
    ],
    server: serverConfig,
  };
});
