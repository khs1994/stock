package main

import (
	"fmt"
	"github.com/urfave/cli"
	"log"
	"os"
)

func main() {
  app := cli.NewApp();
  app.Name = "stock"
  app.Usage = "khs1994.com stock toolkit"
  app.Version = "v18.06"
  app.Action = func(c *cli.Context) error {

  	fmt.Println(c.Args().Get(0))

  	fmt.Println("info")

  	return nil
  }

  err :=app.Run(os.Args)

  if err != nil {
  	log.Fatal(err)
  }
}
