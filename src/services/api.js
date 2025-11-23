// API service layer for backend-ready frontend
const API_BASE_URL =
  import.meta.env.VITE_API_URL || "http://localhost:3001/api";

class ApiService {
  constructor() {
    this.baseURL = API_BASE_URL;
  }

  // Generic request method
  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const config = {
      headers: {
        "Content-Type": "application/json",
        ...options.headers,
      },
      ...options,
    };

    // Add auth token if available (for non-auth endpoints)
    if (!endpoint.includes("/auth") && this.getAuthToken()) {
      config.headers.Authorization = `Bearer ${this.getAuthToken()}`;
    }

    try {
      const response = await fetch(url, config);

      if (!response.ok) {
        const error = await response
          .json()
          .catch(() => ({ message: "Network error" }));
        throw new Error(error.message || `HTTP ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error("API request failed:", error);
      throw error;
    }
  }

  // Auth methods
  async login(credentials) {
<<<<<<< HEAD
    try {
      const response = await this.request('/auth/login', {
        method: 'POST',
        body: JSON.stringify(credentials)
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Login failed');
=======
    // Mock login for development - simulate API delay
    await new Promise((resolve) => setTimeout(resolve, 1000));

    const { email, password } = credentials;

    // Mock user data
    const mockUsers = [
      {
        id: 1,
        email: "admin@chayil.com",
        name: "Admin User",
        role: "admin",
        token: "mock-jwt-token-admin-12345",
      },
      {
        id: 2,
        email: "client@chayil.com",
        name: "Client User",
        role: "client",
        token: "mock-jwt-token-client-12345",
      },
      {
        id: 3,
        email: "analyst@chayil.com",
        name: "Analyst User",
        role: "analyst",
        token: "mock-jwt-token-analyst-12345",
      },
    ];
    for (let i = 0; i < mockUsers.length; i++) {
      const mockUser = mockUsers[i];
      const user = mockUser;
      const mockEmail = mockUser.email;

      if (mockEmail == email && password === "password") {
        console.log(mockEmail);
        return {
          user: user,
          token: user.token,
        };
      }
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
    }
    throw new Error("Invalid email or password");
  }

  async register(userData) {
<<<<<<< HEAD
    try {
      const response = await this.request('/auth/register', {
        method: 'POST',
        body: JSON.stringify(userData)
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Registration failed');
    }
  }

  async forgotPassword(email) {
    try {
      const response = await this.request('/auth/forgot-password', {
        method: 'POST',
        body: JSON.stringify({ email })
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Password reset failed');
    }
  }

  async verify2FA(code) {
    try {
      const response = await this.request('/auth/verify-2fa', {
        method: 'POST',
        body: JSON.stringify({ code })
      });
      return response;
    } catch (error) {
      throw new Error(error.message || '2FA verification failed');
=======
    // Mock register for development
    await new Promise((resolve) => setTimeout(resolve, 1000));

    // Simulate successful registration
    return {
      message: "Registration successful",
      user: {
        id: Date.now(),
        email: userData.email,
        name: userData.name,
        role: "client",
      },
    };
  }

  async forgotPassword(email) {
    // Mock forgot password for development
    await new Promise((resolve) => setTimeout(resolve, 1000));

    // Simulate email sent
    return { message: "Password reset email sent" };
  }

  async verify2FA(code) {
    // Mock 2FA verification for development
    await new Promise((resolve) => setTimeout(resolve, 500));

    if (code === "123456") {
      return {
        token: "mock-jwt-token-2fa-12345",
        user: {
          id: 1,
          email: "admin@chayil.com",
          name: "Admin User",
          role: "admin",
        },
      };
    } else {
      throw new Error("Invalid 2FA code");
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
    }
  }

  async refreshToken() {
<<<<<<< HEAD
    try {
      const response = await this.request('/auth/refresh-token', {
        method: 'POST'
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Token refresh failed');
    }
  }

  async googleLogin(tokenData) {
    try {
      const response = await this.request('/auth/google-login', {
        method: 'POST',
        body: JSON.stringify(tokenData)
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Google login failed');
    }
  }

  async googleSignup(tokenData) {
    try {
      const response = await this.request('/auth/google-signup', {
        method: 'POST',
        body: JSON.stringify(tokenData)
      });
      return response;
    } catch (error) {
      throw new Error(error.message || 'Google signup failed');
    }
=======
    // Mock token refresh for development
    await new Promise((resolve) => setTimeout(resolve, 500));

    return {
      token: "mock-refreshed-jwt-token-12345",
    };
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
  }

  // Admin endpoints
  async getAdminSummary() {
    return this.request("/admin/summary");
  }

  async getClientSummary() {
    return this.request("/client/summary");
  }

  async getClients(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    return this.request(`/admin/clients?${queryString}`);
  }

  async createClient(clientData) {
    return this.request("/admin/clients", {
      method: "POST",
      body: JSON.stringify(clientData),
    });
  }

  async updateClient(clientId, clientData) {
    return this.request(`/admin/clients/${clientId}`, {
      method: "PATCH",
      body: JSON.stringify(clientData),
    });
  }

  async deleteClient(clientId) {
    return this.request(`/admin/clients/${clientId}`, {
      method: "DELETE",
    });
  }

  async getIncidents(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    return this.request(`/incidents?${queryString}`);
  }

  async createIncident(incidentData) {
    return this.request("/incidents", {
      method: "POST",
      body: JSON.stringify(incidentData),
    });
  }

  async updateIncident(incidentId, incidentData) {
    return this.request(`/incidents/${incidentId}`, {
      method: "PATCH",
      body: JSON.stringify(incidentData),
    });
  }

  async getReports(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    return this.request(`/reports?${queryString}`);
  }

  async exportReport(reportData) {
    return this.request("/reports/export", {
      method: "POST",
      body: JSON.stringify(reportData),
    });
  }

  async getTeam() {
    return this.request("/admin/users");
  }

  async updateUserRole(userId, roleData) {
    return this.request(`/admin/roles/${userId}`, {
      method: "PATCH",
      body: JSON.stringify(roleData),
    });
  }

  async getSettings() {
    return this.request("/settings");
  }

  async updateSettings(settingsData) {
    return this.request("/settings", {
      method: "PATCH",
      body: JSON.stringify(settingsData),
    });
  }

  // Client endpoints
  async getClientDashboard() {
    return this.request("/client/dashboard");
  }

  async getClientIncidents() {
    return this.request("/client/incidents");
  }

  async reportIncident(incidentData) {
    return this.request("/client/incidents/report", {
      method: "POST",
      body: JSON.stringify(incidentData),
    });
  }

  async getClientReports() {
    return this.request("/client/reports");
  }

  async getCompliance() {
    return this.request("/compliance");
  }

  async updateCompliance(complianceData) {
    return this.request("/compliance", {
      method: "PATCH",
      body: JSON.stringify(complianceData),
    });
  }

  async getTrainingModules() {
    return this.request("/training/modules");
  }

  async getTrainingProgress() {
    return this.request("/training/progress");
  }

  async updateTrainingProgress(progressData) {
    return this.request("/training/progress", {
      method: "POST",
      body: JSON.stringify(progressData),
    });
  }

  async getMessages() {
    return this.request("/messages");
  }

  async sendMessage(messageData) {
    return this.request("/messages", {
      method: "POST",
      body: JSON.stringify(messageData),
    });
  }

  async getBilling() {
    return this.request("/billing");
  }

  async getAccount() {
    return this.request("/account");
  }

  async updateAccount(accountData) {
    return this.request("/account", {
      method: "PATCH",
      body: JSON.stringify(accountData),
    });
  }

  // Utility methods
  getAuthToken() {
    // In production, token should be in HttpOnly cookie
    // For now, using localStorage for development
    return localStorage.getItem("authToken");
  }

  setAuthToken(token) {
    localStorage.setItem("authToken", token);
  }

  clearAuthToken() {
    localStorage.removeItem("authToken");
  }

  // WebSocket connection for real-time features
  connectWebSocket(endpoint) {
    const wsUrl = this.baseURL.replace("http", "ws") + endpoint;
    return new WebSocket(wsUrl);
  }
}

export const apiService = new ApiService();
export default apiService;
