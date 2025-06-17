const express = require("express");
const router = express.Router();
const db = require("../db");

router.post("/", (req, res) => {
  const { user_id, stock_symbol, title, comment, rating } = req.body;


  if (!user_id || !stock_symbol || !comment) {
    return res.status(400).send("שדות חסרים");
  }

  const sql = `INSERT INTO recommendations (user_id, stock_symbol, title, comment, rating) VALUES (?, ?, ?, ?, ?)`;
  db.query(sql, [user_id, stock_symbol, title || "ללא כותרת", comment, rating], (err, result) => {

    if (err) {
      console.error("שגיאה בהוספת המלצה:", err.message);
      return res.status(500).send("שגיאה בשמירה");
    }
    res.status(201).send("ההמלצה נוספה בהצלחה");
  });
});

// שליפת המלצות לפי משתמש
router.get("/user/:userId", (req, res) => {
  const userId = req.params.userId;
  const sql = "SELECT * FROM recommendations WHERE user_id = ? ORDER BY created_at DESC";
  db.query(sql, [userId], (err, rows) => {
    if (err) {
      console.error("שגיאה בשליפת המלצות:", err.message);
      return res.status(500).send("שגיאה בשרת");
    }
    res.json(rows);
  });
});

router.get("/", (req, res) => {
  const sql = `
    SELECT r.*, u.name AS user_name FROM recommendations r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
  `;
  db.query(sql, (err, results) => {
    if (err) {
      console.error("שגיאה בשליפת כל ההמלצות:", err.message);
      return res.status(500).send("שגיאה בשרת");
    }
    res.json(results);
  });
});

router.delete("/:id", (req, res) => {
  const id = req.params.id;
  const sql = "DELETE FROM recommendations WHERE id = ?";
  db.query(sql, [id], (err, result) => {
    if (err) {
      console.error("שגיאה במחיקת המלצה:", err.message);
      return res.status(500).send("שגיאה במחיקה");
    }
    res.send("המלצה נמחקה");
  });
});

module.exports = router;
