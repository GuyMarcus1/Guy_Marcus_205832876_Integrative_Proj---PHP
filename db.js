const mysql = require("mysql");

const db = mysql.createConnection({
  host: "sql309.byethost17.com",       
  user: "b17_39144683",                
  password: "LAJZhd!@FRR8zTE",     
  database: "b17_39144683_sadkaDB"     
});

db.connect(err => {
  if (err) {
    console.error("❌ שגיאה בחיבור למסד הנתונים:", err.message);
  } else {
    console.log("✅ Connected to remote MySQL (ByetHost)");
  }
});

module.exports = db;
