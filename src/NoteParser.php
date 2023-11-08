<?php

class NoteParser 
{
	private string $note = "";
	private string $extra = "";

	public function parse(string $note): void
	{
		$note_parts = explode("<br/>", $note, 2);
		$this->note = $note_parts[0] ?? "";
		$this->extra = $note_parts[1] ?? "";

		if ($this->note == "null") {
			$this->note = "";
		}
		if ($this->note === "") {
			$this->note = " ";
		}
	}

	public function getNote(): string
	{
		return $this->note;
	}

	public function getExtra(): string
	{
		return $this->extra;
	}
}