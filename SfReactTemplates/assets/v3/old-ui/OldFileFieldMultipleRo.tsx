import React from 'react'
import FileFieldRo from './OldFileFieldRo';

interface Props {
    files: any
}

export default function OldFileFieldMultipleRo(props: Props) {
    const { files } = props;

    return (
        <div className={'flex flex-col gap-1 items-center w-96 rounded-md border border-gray-300 px-2 py-1'}>
            {files.map((f: any, fIndex: number) => {
                return (
                    <FileFieldRo 
                    otherFiles={files}
                        width={"w-full"} 
                        file={f} 
                        key={"file-" + fIndex} />
                );
            })}
        </div>
    )
}
