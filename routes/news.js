const express = require("express");
const router = express.Router();
const db = require("../db");

router.get("/", (req, res) => {
  const sql = "SELECT * FROM news_feed ORDER BY RAND() LIMIT 6";
  db.query(sql, (err, results) => {
    if (err) {
      console.error("שגיאה בשליפת חדשות:", err.message);
      return res.status(500).send("שגיאה בשרת");
    }
    res.json(results);
  });
});

module.exports = router;
