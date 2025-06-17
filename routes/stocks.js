const express = require('express');
const router = express.Router();
const db = require('../db');

router.get("/", (req, res) => {
  db.query("SELECT symbol, name, sector, price FROM stocks", (err, results) => {
    if (err) {
      console.error("DB error:", err);
      return res.status(500).send("שגיאה בשליפת מניות");
    }
    res.json(results);
  });
});
module.exports = router;
