# Basics 1.0.0 validation

The initial build passed 60 offline assertions covering public repositories, caching, simulated concurrency, request limits, HTTP security, output escaping, and topic grouping. PHP syntax checks passed for the four production files and two test files. The ZIP matched the seven files in the historical `htgithubforwp-basics` source directory.

The initial validation did not include installation with a real WordPress database. For package-level testing, use `tests/integration.php` with a disposable WordPress installation. Set `HTGP_INTEGRATION_ROOT`, `HTGP_BASELINE_ZIP`, and `HTGP_CANDIDATE_ZIP`. Create a `.htgp-disposable` marker in the test root and run the script with `install`, `upgrade`, then `verify`.
