MovieSearch
===========

Movie Search - Allow you to search cinemas near you or near a region in Italy (Change it is easy). It uses Google Movies to search.


How it works
===========

I've created a class (see parseresults.php), the call run a search inside http://google.com/movies and fetch the results. <br />
The repo is full of a working site with a Geo-location system. <br />
It uses jQuery Mobile. <br />

GMovies - Array
===========
> Array <br />
> (<br />
>     [0] Array<br />
>         (<br />
>            [name]  Cinema NAME<br />
>             [location] = Cinema Location<br />
>             [movies] =  Array -> Contains an array with all Movies founded<br />
>                 (<br />
>                     [0] = Array<br />
>                         (<br />
>                             [name] = Movie Name<br />
>                             [info] = Movie info<br />
>                             [orari] = Array - Contains all hours of the film<br />
>                                 (<br />
>                                     [0] =  A<br />
>                                     [1] =  B<br />
>                                     [2] =  C<br />
>                                 )<br />
> <br />
>                         )<br />
>                  )<br />
>         )<br />
> )<br />
> <br />
> ETC.<br />
                 

ToDo
===========
* Re-create the parse class, now is very very very very shit.
* Change table structure with JQuery mobile table
