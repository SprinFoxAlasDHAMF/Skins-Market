export const getUser = () => {
  const user = localStorage.getItem("user");
  return user ? JSON.parse(user) : null;
};

export const isLoggedIn = () => !!localStorage.getItem("token");

export const isAdmin = () => getUser()?.role === "admin";

export const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  window.location.href = "/login";
};
