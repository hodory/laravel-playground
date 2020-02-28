import React, {Component} from 'react';
import ReactDOM from 'react-dom';


const THEME_LIGHT_STYLE = {
    background: '#f5f6f9'
};

const THEME_DARK_STYLE = {
    background: '#222'
};

class ThemeSwitcher extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedTheme: 'theme-light',
        };
    }

    _onClick(selectedTheme) {
        this.setState({selectedTheme});
        document.body.className = document.body.className.replace(/theme-\w+/, selectedTheme);
    }

    render() {
        return (
            <div className="flex items-center mr-10">
                <button className="rounded-full w-4 h-4 bg-default border border-accent mr-2 focus:outline-none"
                        onClick={() => this._onClick("theme-light")} style={THEME_LIGHT_STYLE}/>
                <button className="rounded-full w-4 h-4 bg-default border border-accent mr-2 focus:outline-none"
                        onClick={() => this._onClick("theme-dark")} style={THEME_DARK_STYLE}/>
            </div>
        );
    }
}

export default ThemeSwitcher;

if (document.getElementById('app')) {
    ReactDOM.render(<ThemeSwitcher/>, document.getElementById('theme-switcher'));
}
