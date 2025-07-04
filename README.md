# TradeMe - מערכת מסחר, המלצות ומידע פיננסי

ברוכים הבאים ל-**TradeMe** – אתר אינטראקטיבי חכם המאפשר למשתמשים לעקוב אחר מניות, לתת המלצות, לקבל מידע פיננסי ולהתעדכן בחדשות כלכליות.
המטרה המרכזית של האתר היא להעניק למשתמשים פלטפורמה עשירה ואישית, שדרכה יוכלו:

* לנהל תיק אישי של מניות למעקב
* לקרוא ולהוסיף המלצות השקעה
* להרחיב את הידע הפיננסי שלהם
* לקבל תמונת מצב עדכנית מהעולם הכלכלי

שם משתמש רשום על מנת לבצע את הפעולות כמו שצריך לבדיקה - 
שם משתמש - guy@gmail.com
סיסמא - 123456

## תכונות עיקריות לפי דפים באתר

### 🛡 דף אדמין (admin.html)

* נגישות אך ורק למשתמשים בעלי הרשאות מנהל (admin)
* צפייה ברשימת כל המשתמשים באתר
* מחיקת משתמשים קיימים
* צפייה ומחיקה של המלצות משתמשים

### 📊 דף דאשבורד (dashboard.html)

* מציג מניות מקטגוריות שונות (סקטורים)
* לכל סקטור יש תפריט נפתח המציג מניות רלוונטיות
* ניתן להוסיף מניות מהדאשבורד לתיק האישי
* מחירי המניות מוצגים ומעודכנים בהתאם

### 📁 דף תיק אישי (personal.html)

* מציג את כל המניות שנוספו למעקב ע"י המשתמש
* משמש לאימות בהוספת המלצות (לבדוק אם המשתמש מחזיק מניה)
* ניתן להסיר מניות מהתיק בלחיצת כפתור

### ⭐ דף המלצות (recommendations.html)

* מציג את כל ההמלצות של משתמשים אחרים
* אפשרות לסינון לפי שם מניה
* סינון לפי סטטוס "מחזיק/לא מחזיק" במניה

### 📝 דף פרופיל (profile.html)

* משתמש יכול לעדכן את פרטיו האישיים, הסיסמה והרקע האישי שלו
* בחירת תחומי התמחות (קריפטו, מניות, מדדים וכו')
* מכיל קישור לדף מתן המלצה

### ➕ דף מתן המלצה (recommend.html)

* בחירת מניה מתוך הרשימה (שמקושרת לדאשבורד)
* סימון האם המשתמש מחזיק במניה (נבדק מול התיק האישי!)
* הגבלת כתיבת המלצה אם טען שמחזיק ולא באמת בתיק

### 📚 דף מידע פיננסי (info.html)

* מכיל תיבת מידע נפתחת לכל נושא פיננסי שנבחר
* משמש להעשרת הידע הפיננסי של המשתמש
* כולל קישור לדף מחשבון ריבית דריבית

### 🧮 דף ריבית דריבית (interest.php)

* מחשבון PHP אינטראקטיבי לחישוב ריבית דריבית
* הזנת סכום, אחוז ריבית, וכמות שנים
* תוצאה מחושבת של הסכום הסופי
* כולל סרטון יוטיוב מוטמע המסביר את הנושא

### 🗞 דף חדשות (news.html)

* מציג חדשות כלכליות מתחלפות עם תאריך, כותרת, קטגוריה ותמונה
* נשלפות ממסד הנתונים (כולל כתבות ותמונות תואמות)

### 📬 דף צור קשר (contact.html)

* שליחת טופס למייל של מנהל האתר
* ניתן להשאיר שם, כתובת מייל והודעה

### 🏠 דף הבית (index.html)

* מציג מידע כללי על מטרת האתר
* כשמשתמש מחובר: מוצג לו שלום אישי עם שמו

### 👤 דף המייסד (about\_me.html)

* מציג פרטים על מקים האתר
* כפתור לחשיפת מידע נוסף

### 🔐 דפי התחברות והרשמה (login.html + register.html)

* משתמשים לא רשומים מופנים להרשמה/התחברות
* דפי התחברות והרשמה מבצעים ולידציות ומובילים לכניסה מלאה

## ניווט ובקרה כללית

* כל דף באתר כולל תפריט ניווט (Navbar) ברור ונגיש
* קיימת בדיקה אוטומטית האם המשתמש מחובר, אחרת מועבר לדף התחברות
* שמירת מידע על המשתמש המקומי מתבצעת באמצעות localStorage

## יתרונות האתר

* ממשק עשיר ומותאם אישית לכל משתמש
* שליטה נוחה וניהול תיק אישי
* אימות אמין להמלצות על בסיס החזקות אמיתיות
* שיפור ההבנה הפיננסית באמצעות מידע, חדשות ומחשבון ייחודי
