const express = require("express");
const router = express.Router();
const db = require("../db");

// Register route
router.post("/register", (req, res) => {
  console.log("POST /register request received");
  console.log("Request body:", req.body);

  const { name, email, password } = req.body;

  if (!name || !email || !password) {
    return res.status(400).send("Missing required fields");
  }

  const sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
  db.query(sql, [name, email, password], (err, result) => {
    if (err) {
      console.error("Error during registration:", err.message);
      return res.status(500).send("Registration failed");
    }

    console.log("User registered successfully:", result);
    res.status(201).send("Registration successful");
  });
});

router.post("/login", (req, res) => {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).send("Missing email or password");
  }

  const sql = "SELECT id, name, email, role FROM users WHERE email = ? AND password = ?";
  db.query(sql, [email, password], (err, results) => {
    if (err) {
      console.error("שגיאה ב-login:", err.message);
      return res.status(500).send("שגיאה בשרת");
    }
    if (results.length === 0) {
      return res.status(401).send("פרטי התחברות שגויים");
    }

    const user = results[0];
    res.json({
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role 
    });
  })
});

module.exports = router;
