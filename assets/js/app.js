/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import React from 'react';
import ReactDOM from 'react-dom';

import Items from './Components/Items';

class App extends React.Component {
    constructor() {
        super();

        this.state = {
            entries: []
        };
    }

    componentDidMount() {
        fetch('get-movies')
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    entries
                });
                $('.star-rates').barrating({
                    theme: 'fontawesome-stars'
                });
            });
    }

    static initNewCard(el) {
        $('.movie-card:last').after(el);
        el.find('select').barrating({
            theme: 'fontawesome-stars'
        });
        el.find('.ok-btn').on('click', function (e) {
            App.save(e);
        });
        el.find('.no-btn').on('click', function (e) {
            App.save(e);
        });
    }

    static save(id) {
        var el = $(id.currentTarget);
        el.prop('disabled', true);
        var card = el.closest('.card-body');
        var mark = -1;
        if (el.hasClass('ok-btn')) {
            mark = card.find('select.star-rates').val();
        }
        $.ajax({
            type: "POST",
            url: 'review',
            data: {
                movieId: card.find('input.movie_id').val(),
                mark: mark
            },
            success: function (response) {
                el.closest('.card').fadeOut('slow');
                var newCard = $(response);
                App.initNewCard(newCard);
            },
            error: function () {
                alert('Coś poszło nie tak :(');
            }
        });
    }

    render() {
        return (
            <div className="row">
                {this.state.entries.map(
                    (entry) => (
                        <Items
                            id={entry.id}
                            title={entry.title}
                            photo={entry.photo}
                            mark={entry.mark}
                            director={entry.director_name}
                            country={entry.countries_string}
                            genre={entry.genres_string}
                            fun={App.save}
                        >
                        </Items>
                    )
                )}
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
