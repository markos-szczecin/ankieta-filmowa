import React from 'react';


const Items = ({ id, title, body, fun }) => (
    <div key={id} className="card col-md-4" style={{width:200}}>
        <div className="card-body">
            <p>{id}</p>
            <h4 className="card-title">{title}</h4>
            <p className="card-text">{body}</p>
            <button onClick={fun.bind(this)} className="btn btn-primary">More Details</button>
        </div>
    </div>
);

export default Items;