# Pick 'em
Try to predict the outcome of NFL games using historical data

## Models
Models live in the `/models` directory and can be invoked on the command like.  For example, `php run-model.php model-slug` would run a model in `/models/model-slug.php`.  Each model must define a `pickem_model()` function that accepts a `$season` (year), `$week` (integer) and `$teams` (array of team names).  `pickem_model()` should return a team name (string) to be compared against the actual winner of the game.