import React from 'react';


const Items = ({ id, title, photo, mark, director, country, genre, fun }) => (
    <div className="movie-card card col-md-4 col-12">
        <div className="card-body">
            <input className="movie_id" type="hidden" value={id} name="movie_id"/>
            <div className="row">
                <div className="col-md-12"><h4 className="card-title">{title}</h4></div>
            </div>
            <div className="row">
                <div className="col-md-4 col-12 text-center">
                    <img className="move-poster" src={photo}/>
                </div>
                <div className="col-md-8 col-12">
                    <div className="row movie-detail">
                        <div className="col-md-5">
                            <div>Kraj:</div>
                            <div><small>{country}</small></div>
                        </div>
                        <div className="col-md-7">
                            <div>Reżyser:</div>
                            <div><small>{director}</small></div>
                        </div>
                    </div>
                    <div className="row movie-detail">
                        <div className="col-md-12">
                            <div>Gatunek:</div>
                            <div><small>{genre}</small></div>
                        </div>
                    </div>
                    <div className="row">
                        <select className="star-rates">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option selected value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div>
                        <button onClick={fun.bind(this)} className="btn btn-primary btn-sm ok-btn">
                            Zatwierdź
                        </button>
                    </div>
                    <div>
                        <button onClick={fun.bind(this)} className="btn btn-danger btn-sm no-btn">Nie znam</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
);

export default Items;