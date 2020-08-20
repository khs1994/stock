package _import

import (
	"database/sql"
	"fmt"

	_ "github.com/go-sql-driver/mysql"
)

func main() {
	db, err := sql.Open("mysql", "root:mytest@tcp(127.0.0.1:3306)?stock?charset=utf8mb4")

	fmt.Println(err)

	stmt, err := db.Prepare("")

	stmt.Exec()
}
