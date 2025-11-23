import { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import { useTheme } from "../context/ThemeContext";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import {
  FaUsers,
  FaExclamationTriangle,
  FaCheckCircle,
  FaBolt,
  FaUserPlus,
  FaFileAlt,
  FaShieldAlt,
  FaSearch,
  FaCog,
  FaExclamationCircle,
} from "react-icons/fa";
import StatsCard from "../components/admin/StatsCard";
import ThreatMap from "../components/admin/ThreatMap";
import RecentLogs from "../components/admin/RecentLogs";
import { apiService } from "../services/api";

export default function AdminDashboardNew() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [dashboardData, setDashboardData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await apiService.getAdminSummary();
      setDashboardData(data);
    } catch (error) {
      console.error("Failed to fetch dashboard data:", error);
      setError(error.message || "Failed to load dashboard data");
      setDashboardData(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDashboardData();
  }, []);

  if (loading) {
    return (
<<<<<<< HEAD
      <div className="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center" style={{ backgroundImage: `url('${import.meta.env.BASE_URL}images/background5.jpg')` }}>
=======
      <div
        className="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center"
        style={{ backgroundImage: "url('/background5.jpg')" }}
      >
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-400 mx-auto mb-4"></div>
          <div className="text-cyan-300 text-lg">Loading Dashboard...</div>
        </div>
      </div>
    );
  }

  if (error) {
    return (
<<<<<<< HEAD
      <div className="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center" style={{ backgroundImage: `url('${import.meta.env.BASE_URL}images/background5.jpg')` }}>
        <div className="min-h-screen bg-black/50 backdrop-blur-sm flex items-center justify-center">
=======
      <div
        className="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center"
        style={{ backgroundImage: "url('/background5.jpg')" }}
      >
        <div className="min-h-screen bg-black/50 backdrop-blur-sm flex items-center w-full justify-center">
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
          <div className="bg-gray-900/95 backdrop-blur-md border border-red-500/30 rounded-lg p-8 max-w-md w-full text-center">
            <div className="mx-auto h-16 w-16 bg-red-500/20 rounded-full flex items-center justify-center mb-4">
              <svg
                className="h-8 w-8 text-red-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                />
              </svg>
            </div>
            <h2 className="text-xl font-semibold text-red-300 mb-2">
              Failed to Load Dashboard
            </h2>
            <p className="text-gray-400 mb-6">{error}</p>
            <div className="flex space-x-3 justify-center">
              <button
                onClick={fetchDashboardData}
                className="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-md transition-colors duration-200"
              >
                Try Again
              </button>
              <button
                onClick={() => navigate("/")}
                className="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors duration-200"
              >
                Go Home
              </button>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
<<<<<<< HEAD
    <div className="min-h-screen bg-cover bg-center bg-no-repeat" style={{ backgroundImage: `url('${import.meta.env.BASE_URL}images/background5.jpg')` }}>
=======
    <div
      className="min-h-screen bg-cover bg-center bg-no-repeat"
      style={{ backgroundImage: "url('/background5.jpg')" }}
    >
>>>>>>> 6a637071ab5a3889e785dc0379a33803f0570d99
      <div className="min-h-screen bg-black/50 backdrop-blur-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            {/* Header */}
            <div className="flex justify-between items-center mb-8">
              <div>
                <h1 className="text-3xl font-bold text-cyan-300">
                  Admin Dashboard
                </h1>
                <p className="text-gray-400 mt-2">
                  Welcome back, {user?.name || "Admin"}
                </p>
              </div>
              <button
                onClick={logout}
                className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200"
              >
                Logout
              </button>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
              <StatsCard
                title="Total Clients"
                value={dashboardData?.totalClients || 24}
                change={12}
                icon={<FaUsers className="w-6 h-6" />}
                color="teal"
              />
              <StatsCard
                title="Active Threats"
                value={dashboardData?.activeThreats || 12}
                change={-8}
                icon={<FaExclamationTriangle className="w-6 h-6" />}
                color="red"
              />
              <StatsCard
                title="System Health"
                value={`${dashboardData?.systemHealth || 98}%`}
                change={2}
                icon={<FaCheckCircle className="w-6 h-6" />}
                color="green"
              />
              <StatsCard
                title="Incidents Today"
                value={dashboardData?.incidentsToday || 8}
                change={15}
                icon={<FaBolt className="w-6 h-6" />}
                color="yellow"
              />
            </div>

            {/* Main Content Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
              {/* Threat Map - Takes 2 columns on large screens */}
              <div className="lg:col-span-2">
                <ThreatMap />
              </div>

              {/* Recent Logs - Takes 1 column */}
              <div>
                <RecentLogs />
              </div>
            </div>

            {/* Quick Actions */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.4 }}
              className="bg-gray-900/95 backdrop-blur-md border border-teal-500/30 rounded-lg p-6"
            >
              <h3 className="text-lg font-semibold text-cyan-300 mb-4">
                Quick Actions
              </h3>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button
                  onClick={() => navigate("/admin/clients")}
                  className="p-4 bg-teal-600 hover:bg-teal-700 text-white rounded-md transition-all duration-200 transform hover:scale-105 flex flex-col items-center space-y-2"
                >
                  <FaUserPlus className="h-6 w-6" />
                  <span className="text-sm font-medium">Manage Clients</span>
                </button>
                <button
                  onClick={() => navigate("/admin/reports")}
                  className="p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-all duration-200 transform hover:scale-105 flex flex-col items-center space-y-2"
                >
                  <FaFileAlt className="h-6 w-6" />
                  <span className="text-sm font-medium">View Reports</span>
                </button>
                <button
                  onClick={() => navigate("/admin/incidents")}
                  className="p-4 bg-red-600 hover:bg-red-700 text-white rounded-md transition-all duration-200 transform hover:scale-105 flex flex-col items-center space-y-2"
                >
                  <FaExclamationTriangle className="h-6 w-6" />
                  <span className="text-sm font-medium">Incidents</span>
                </button>
                <button
                  onClick={() => navigate("/admin/threats")}
                  className="p-4 bg-green-600 hover:bg-green-700 text-white rounded-md transition-all duration-200 transform hover:scale-105 flex flex-col items-center space-y-2"
                >
                  <FaShieldAlt className="h-6 w-6" />
                  <span className="text-sm font-medium">Threat Intel</span>
                </button>
                <button
                  onClick={() => navigate("/admin/settings")}
                  className="p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-all duration-200 transform hover:scale-105 flex flex-col items-center space-y-2"
                >
                  <FaCog className="h-6 w-6" />
                  <span className="text-sm font-medium">Settings</span>
                </button>
              </div>
            </motion.div>
          </motion.div>
        </div>
      </div>
    </div>
  );
}
