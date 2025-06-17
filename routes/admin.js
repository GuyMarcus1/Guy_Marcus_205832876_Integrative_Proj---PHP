const express = require("express");
const router = express.Router();
const db = require("../db");

router.get("/users", (req, res) => {
  const sql = "SELECT id, name, email, role FROM users";
  db.query(sql, (err, results) => {
    if (err) {
      console.error("Error fetching users:", err.message);
      return res.status(500).send("שגיאה בשליפת משתמשים");
    }
    res.json(results);
  });
});
router.delete("/user/:id", (req, res) => {

  const userId = req.params.id;
  const sql = "DELETE FROM users WHERE id = ?";
  db.query(sql, [userId], (err, result) => {
    if (err) {
      console.error("שגיאה במחיקת משתמש:", err.message);
      return res.status(500).send("שגיאה במחיקת משתמש.");
    }
    if (result.affectedRows === 0) {
      return res.status(404).send("משתמש לא נמצא.");
    }
    res.status(200).send("המשתמש נמחק בהצלחה.");
  });
});

module.exports = router;
