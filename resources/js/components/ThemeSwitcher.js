import React, {Component} from 'react';
import ReactDOM from 'react-dom';


const THEME_LIGHT_STYLE = {
    background: '#f5f6f9'
};

const THEME_DARK_STYLE = {
    background: '#222'
};

const THEME_LIGHT = {
    color: 'theme-light',
    style: {background: '#f5f6f9'}
};

const THEME_DARK = {
    color: 'theme-dark',
    style: {background: '#222'}
};

class ThemeSwitcher extends Component {
    constructor(props) {
        super(props);
        this.state = {
            themes: [
                THEME_LIGHT,
                THEME_DARK
            ],
            selectedTheme: 'theme-light',
        };
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if (localStorage.getItem('theme')) {
            const theme = localStorage.getItem('theme');
            document.body.className = document.body.className.replace(/theme-\w+/, theme);
            return {selectedTheme: theme};
        }
    }

    _onClick(selectedTheme) {
        this.setState({selectedTheme});
        localStorage.setItem('theme', selectedTheme);
        document.body.className = document.body.className.replace(/theme-\w+/, selectedTheme);
    }

    renderMethod() {
        return this.state.themes.map((theme, index) => {
            return (
                <button key={index}
                        className="rounded-full w-4 h-4 bg-default border border-accent mr-2 focus:outline-none"
                        onClick={() => this._onClick(theme.color)} style={theme.style}/>
            );
        })
    }

    render() {
        return (
            <div className="flex items-center mr-10">
                {this.renderMethod()}
            </div>
        );
    }
}

export default ThemeSwitcher;

if (document.getElementById('app')) {
    ReactDOM.render(<ThemeSwitcher/>, document.getElementById('theme-switcher'));
}
