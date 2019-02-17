/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

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
        fetch('https://jsonplaceholder.typicode.com/posts/')
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    entries
                });
            });
    }

    save(id) {
        $(id.currentTarget).closest('.card').fadeOut('slow');
    }

    render() {
        return (
            <div className="row">
                {this.state.entries.map(
                    ({ id, title, body }) => (
                        <Items
                            key={id}
                            title={title}
                            body={body}
                            fun={this.save}
                        >
                        </Items>
                    )
                )}
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
