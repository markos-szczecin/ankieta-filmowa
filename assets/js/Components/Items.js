import React from 'react';


const Items = ({ id, title, photo, mark, director, country, genre, fun }) => (
    <div className="movie-card card col-lg-4 col-md-6 col-12">
        <div className="card-body">
            <input className="movie_id" type="hidden" value={id} name="movie_id"/>
            <div className="row">
                <div className="col-12 text-center"><h4 className="card-title">{title}</h4></div>
            </div>
            <div className="row">
                <div className="col-lg-4 col-12 text-center">
                    <img className="move-poster" src={photo}/>
                </div>
                <div className="col-lg-8 col-12">
                    <div className="row movie-detail">
                        <div className="col-5">
                            <div>Kraj:</div>
                            <div><small>{country}</small></div>
                        </div>
                        <div className="col-7">
                            <div>Reżyser:</div>
                            <div><small>{director}</small></div>
                        </div>
                    </div>
                    <div className="row movie-detail">
                        <div className="col-12">
                            <div>Gatunek:</div>
                            <div><small>{genre}</small></div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 text-center">
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
                    </div>
                    <div className="row d-none d-sm-flex">
                        <div className="col-12 col-sm-6">
                            <button onClick={fun.bind(this)} className="btn btn-danger btn-sm no-btn col-12 float-sm-left">Nie znam</button>
                        </div>
                        <div className="col-12 col-sm-6">
                            <button onClick={fun.bind(this)} className="btn btn-primary btn-sm ok-btn col-12 float-sm-right">
                                Zatwierdź
                            </button>
                        </div>
                    </div>
                    <div className="row d-flex d-sm-none">
                        <div className="col-12 col-sm-6">
                            <button onClick={fun.bind(this)} className="btn btn-primary btn-sm ok-btn col-12 float-sm-right">
                                Zatwierdź
                            </button>
                        </div>
                        <div className="col-12 col-sm-6">
                            <button onClick={fun.bind(this)} className="btn btn-danger btn-sm no-btn col-12 float-sm-left">Nie znam</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
);

export default Items;