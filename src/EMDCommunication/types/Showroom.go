package types

type Showroom struct {
	Id int
	Score int
	Name string
	Slug string
	Provider Provider
	Vertical Vertical
	Extra_data string
}