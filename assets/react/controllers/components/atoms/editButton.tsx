const EditIcon = require('../../../../icons/editIcon.png');

type Props = {
    onClick: (e: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function EditButton({ onClick }: Props) {
    return (
        <span
            onClick={onClick}
            className="cursor-pointer"
            title="Modifier"
        >
            <img 
                src={EditIcon} 
                alt="Modifier" 
                className="w-5 h-5 rounded-full shadow" 
            />
        </span>
    );
}