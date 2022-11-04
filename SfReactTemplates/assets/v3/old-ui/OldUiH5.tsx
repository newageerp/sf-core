
interface Props {
    children: any
    className?: string
    icon?: string,
    iconClassName?: string,
    iconLoading?: boolean,
}

const primaryColor = "text-nsecondary-600";

export function OldUiH5(props: Props) {
    const className = ['text-lg '];
    if (!(props.className && props.className.indexOf("text-") >= 0)) {
        className.push(primaryColor);
    }
    if (props.className) {
        className.push(props.className)
    }
    if (props.icon) {
        const iconClassName = ['text-sm', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h5 className={className.join(' ')}>{props.children}</h5>
            </div>
        )
    }
    return <h5 className={className.join(' ')}>{props.children}</h5>
}

export function OldUiH4(props: Props) {
    const className = ['text-xl '];
    if (!(props.className && props.className.indexOf("text-") >= 0)) {
        className.push(primaryColor);
    }
    if (props.className) {
        className.push(props.className)
    }
    if (props.icon) {
        const iconClassName = ['text-base', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h4 className={className.join(' ')}>{props.children}</h4>
            </div>
        )
    }
    return <h4 className={className.join(' ')}>{props.children}</h4>
}

export function OldUiH2(props: Props) {
    const className = ['text-3xl']
    if (!(props.className && props.className.indexOf("text-") >= 0)) {
        className.push(primaryColor);
    }
    if (props.className) {
        className.push(props.className)
    }

    if (props.icon) {
        const iconClassName = ['text-xl', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h2 className={className.join(' ')}>{props.children}</h2>
            </div>
        )
    }

    return <h2 className={className.join(' ')}>{props.children}</h2>
}


export function OldUiH3(props: Props) {
    const className = ['text-2xl'];
    if (!(props.className && props.className.indexOf("text-") >= 0)) {
        className.push(primaryColor);
    }
    if (props.className) {
        className.push(props.className)
    }

    if (props.icon) {
        const iconClassName = ['text-lg', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h3 className={className.join(' ')}>{props.children}</h3>
            </div>
        )
    }

    return <h3 className={className.join(' ')}>{props.children}</h3>
}

export function OldUiH3Toolbar(props: Props) {
    const className = ['text-nsecondary-200 text-2xl'];
    if (props.className) {
        className.push(props.className)
    }

    if (props.icon) {
        const iconClassName = ['text-lg', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h3 className={className.join(' ')}>{props.children}</h3>
            </div>
        )
    }

    return <h3 className={className.join(' ')}>{props.children}</h3>
}


export function OldUiH6(props: Props) {
    const className = ['text-base ']
    if (!(props.className && props.className.indexOf("text-") >= 0)) {
        className.push(primaryColor);
    }
    if (props.className) {
        className.push(props.className)
    }
    if (props.icon) {
        const iconClassName = ['text-xs', primaryColor];
        if (props.iconLoading) {
            iconClassName.push("animate-spin " + props.icon);
        } else {
            iconClassName.push(props.icon);
        }
        if (props.children) {
            iconClassName.push("mr-1");
        }
        if (props.iconClassName) {
            iconClassName.push(props.iconClassName);
        }

        className.push('flex-grow')
        return (
            <div className={"flex items-center"}>
                <i className={iconClassName.join(" ")} />
                <h6 className={className.join(' ')}>{props.children}</h6>
            </div>
        )
    }
    return <h6 className={className.join(' ')}>{props.children}</h6>
}