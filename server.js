const express = require("express");
const app = express();
const path = require("path");
const db = require("./db");

// ראוטים
const portfolioRoutes = require("./routes/portfolio");
const recommendRoutes = require("./routes/recommend");
const userRoutes = require("./routes/users");
const adminRoutes = require("./routes/admin");
const newsRoutes = require("./routes/news");
const stocksRoutes = require("./routes/stocks");

app.use(express.json());

// שימוש בנתיבים
app.use("/api/admin", adminRoutes);
app.use("/api/portfolio", portfolioRoutes);
app.use("/api/recommend", recommendRoutes);
app.use("/api", userRoutes);
app.use("/api/news", newsRoutes);
app.use("/api/stocks", stocksRoutes);

app.use(express.static("public"));


app.get("/test-db", (req, res) => {
  db.query("SELECT 1 + 1 AS result", (err, results) => {
    if (err) {
      console.error("❌ שגיאה בחיבור למסד:", err.message);
      return res.status(500).send("בעיה במסד הנתונים");
    }
    res.send("חיבור לבסיס נתונים תקין ✔️ תוצאה: " + results[0].result);
  });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`✅ השרת פעיל בכתובת http://localhost:${PORT}`);
});
