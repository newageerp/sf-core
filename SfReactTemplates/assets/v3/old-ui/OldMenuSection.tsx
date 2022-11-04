import React from "react";

interface Props {
    title: string,
}

export default function OldMenuSection(props: Props) {
    const {title} = props;
    return (
        <div className={"text-nsecondary-300 uppercase px-2 mt-2"}>{title}</div>
    )
}
