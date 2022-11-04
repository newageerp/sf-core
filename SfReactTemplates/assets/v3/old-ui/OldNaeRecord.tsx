import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import React, { useContext, useEffect, useState, Fragment } from "react";
import { getDepenciesForField } from "../../../config/fields/fieldDependencies";
import { SFSSocketService } from "../navigation/NavigationComponent";
import { getElementFieldsToReturn, getSchemaClassNameBySchema, INaeViewSettings } from "../utils";
import { useUIBuilder } from './builder/OldUIBuilderProvider';

export interface RecordProviderValue {
    loadTime: number;
    reloadData: () => Promise<any>;
    reloading: boolean;
    viewStackIndex: number;
    element: any;
    schema: string,
}

export const NaeRecordContext = React.createContext<RecordProviderValue>({
    loadTime: 0,
    reloadData: () => {
        return new Promise(() => {

        });
    },
    reloading: false,
    viewStackIndex: 0,
    element: {},
    schema: '',
});

export const useNaeRecord = () => useContext(NaeRecordContext);

export interface RecordProps {
    children: any;
    schema: string;
    id: number | string;
    fieldsToReturn?: string[];
    viewType?: string;
    defaultViewIndex?: number;
    viewId: string,
    showOnEmpty?: boolean,
}

export const NaeRecordProvider = ({ children, schema, id, fieldsToReturn, viewType, defaultViewIndex, viewId, showOnEmpty }: RecordProps) => {
    const {getViewFieldsForSchema} = useUIBuilder();
    
    let _fieldsToReturn: string[] = [];
    if (fieldsToReturn) {
        _fieldsToReturn = fieldsToReturn;
    } else {
        const viewSettings = getViewFieldsForSchema(
            schema,
            viewType
        );
        const viewFields: INaeViewSettings = viewSettings
            ? viewSettings
            : { fields: [], schema: "", type: "" };
        _fieldsToReturn =
            getElementFieldsToReturn(
                viewFields,
                (key: string) => getDepenciesForField(schema, key)
            );
    }

    const [element, setElement] = useState();

    const naeRecordData = useNaeRecord();

    const viewStackIndex = defaultViewIndex !== undefined
        ? defaultViewIndex : naeRecordData.viewStackIndex + 1;

    const [schemaGetData, schemaGetDataParams] = OpenApi.useUList<any>(
        schema,
        _fieldsToReturn
    );
    const getData = () => {
        return schemaGetData([{ classicMode: true, and: [["i.id", "=", id, true]] }], 1, 1)
    };
    useEffect(() => {
        getData();
    }, [id, schema, fieldsToReturn]);

    const [loadTime, setLoadTime] = useState(0);
    const _id: number =
        typeof id === "number"
            ? id
            : isNaN(parseInt(id, 10))
                ? 0
                : parseInt(id, 10);

    useEffect(() => {
        SFSSocketService.addCallbackEntity(
            getSchemaClassNameBySchema(schema),
            _id,
            viewId,
            getData
        );
        return () => {
            SFSSocketService.removeCallbackEntity(
                getSchemaClassNameBySchema(schema),
                _id,
                viewId
            );
        };
    }, [_id]);

    useEffect(() => {
        if (schemaGetDataParams.data.records === 1) {
            setElement(schemaGetDataParams.data.data[0]);
            setLoadTime(new Date().getTime());
        }
    }, [schemaGetDataParams.data]);

    const reloadData = () => {
        return getData();
    }

    if (!element && !showOnEmpty) {
        return <Fragment />
    }

    return (
        <NaeRecordContext.Provider value={{ reloading: schemaGetDataParams.loading, reloadData, loadTime, viewStackIndex, element, schema }}>
            {children}
        </NaeRecordContext.Provider>
    );
};
