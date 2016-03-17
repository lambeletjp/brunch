sub vcl_recv {
    // Add a Surrogate-Capability header to announce ESI support.
    set req.http.Surrogate-Capability = "abc=ESI/1.0";
}

sub vcl_fetch {
    /*
    Check for ESI acknowledgement
    and remove Surrogate-Control header
    */
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;

        // For Varnish >= 3.0
        set beresp.do_esi = true;
        // For Varnish < 3.0
        // esi;
    }
    /* By default Varnish ignores Cache-Control: nocache
    (https://www.varnish-cache.org/docs/3.0/tutorial/increasing_your_hitrate.html#cache-control),
    so in order avoid caching it has to be done explicitly */
    if (beresp.http.Pragma ~ "no-cache" ||
         beresp.http.Cache-Control ~ "no-cache" ||
         beresp.http.Cache-Control ~ "private") {
        return (hit_for_pass);
    }
}

/*
 Connect to the backend server
 on the local machine on port 8080
 */
backend default {
    .host = "127.0.0.1";
    .port = "8080";
}

sub vcl_recv {
    /*
    Varnish default behavior doesn't support PURGE.
    Match the PURGE request and immediately do a cache lookup,
    otherwise Varnish will directly pipe the request to the backend
    and bypass the cache
    */
    if (req.request == "PURGE") {
        return(lookup);
    }
}

sub vcl_hit {
    // Match PURGE request
    if (req.request == "PURGE") {
        // Force object expiration for Varnish < 3.0
        set obj.ttl = 0s;
        // Do an actual purge for Varnish >= 3.0
        // purge;
        error 200 "Purged";
    }
}

sub vcl_miss {
    /*
    Match the PURGE request and
    indicate the request wasn't stored in cache.
    */
    if (req.request == "PURGE") {
        error 404 "Not purged";
    }
}

