const express = require("express");
const router = express.Router();
const db = require("../db");

router.post("/", (req, res) => {
  const { user_id, stock_symbol } = req.body;

  if (!user_id || !stock_symbol) {
    return res.status(400).send("שדות חסרים");
  }

  const checkSql = `SELECT * FROM portfolio WHERE user_id = ? AND stock_symbol = ?`;
  db.query(checkSql, [user_id, stock_symbol], (err, rows) => {
    if (err) {
      console.error("שגיאה בבדיקה:", err.message);
      return res.status(500).send("שגיאה בבדיקה");
    }

    if (rows.length > 0) {
      return res.status(409).send("כבר במעקב");
    }

    const insertSql = `INSERT INTO portfolio (user_id, stock_symbol) VALUES (?, ?)`;
    db.query(insertSql, [user_id, stock_symbol], (err, result) => {
      if (err) {
        console.error("שגיאה בשמירה:", err.message);
        return res.status(500).send("שגיאה בשמירה");
      }
      res.status(201).send("נוסף למעקב");
    });
  });
});

router.get("/:userId", (req, res) => {
  const userId = req.params.userId;
  const sql = `SELECT stock_symbol FROM portfolio WHERE user_id = ?`;
  db.query(sql, [userId], (err, rows) => {
    if (err) {
      console.error("שגיאה בשליפה:", err.message);
      return res.status(500).send("שגיאה בשרת");
    }
    res.json(rows);
  });
});

router.delete("/:userId/:symbol", (req, res) => {
  const { userId, symbol } = req.params;
  const sql = `DELETE FROM portfolio WHERE user_id = ? AND stock_symbol = ?`;
  db.query(sql, [userId, symbol], (err, result) => {
    if (err) {
      console.error("שגיאה במחיקה:", err.message);
      return res.status(500).send("שגיאה במחיקה");
    }
    res.send("הוסר מהמעקב");
  });
});

module.exports = router;
