import pkg from "pg";
import dotenv from "dotenv";

dotenv.config();

const { Pool } = pkg;

console.log("DATABASE_URL from .env:", process.env.DATABASE_URL);

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: { rejectUnauthorized: false },
  host: "db.qxyvgmuixrllsdqtglqu.supabase.co",
  port: 5432,
  max: 1,
  idleTimeoutMillis: 30000
});


async function testConnection() {
  try {
    const res = await pool.query("SELECT NOW()");
    console.log("Connected! Current time:", res.rows[0]);
    process.exit(0);
  } catch (err) {
    console.error("Connection failed:", err.message);
    process.exit(1);
  }
}

testConnection();
